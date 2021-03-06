<?php
/**
 * Created by PhpStorm.
 * @author domenico domenico@translated.net / ostico@gmail.com
 * Date: 13/03/17
 * Time: 19.48
 *
 */

namespace API\App;


use API\V2\Exceptions\AuthorizationError;
use Outsource\ConfirmationDao;
use Outsource\TranslatedConfirmationStruct;
use Utils;

class OutsourceConfirmationController extends AbstractStatefulKleinController {

    public function confirm() {

        $params = filter_var_array( $this->request->params(), array(
                'id_job'   => FILTER_SANITIZE_STRING,
                'password' => FILTER_SANITIZE_STRING,
                'payload'  => FILTER_SANITIZE_STRING,
        ) );

        $payload = \SimpleJWT::getValidPayload( $params[ 'payload' ] );

        if ( $params[ 'id_job' ] != $payload[ 'id_job' ] || $params[ 'password' ] != $payload[ 'password' ] ) {
            throw new AuthorizationError( "Invalid Job" );
        }

        $confirmationStruct = new TranslatedConfirmationStruct( $payload );
        $confirmationStruct->create_date = Utils::mysqlTimestamp( time() );
        ConfirmationDao::insertStruct( $confirmationStruct, [ 'ignore' => true, 'no_nulls' => true ] );

        $confirmationArray = $confirmationStruct->toArray();
        unset( $confirmationArray['id'] );
        $this->response->json( [ 'confirm' => $confirmationArray ] );

    }

}