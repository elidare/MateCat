<?php

class Chunks_ChunkDao extends DataAccess_AbstractDao {

    /**
     * @param $id
     * @param $password
     *
     * @return mixed
     * @throws \Exceptions\NotFoundError
     */
    public static function getByIdAndPassword( $id, $password ) {
        $conn = Database::obtain()->getConnection();

        $stmt = $conn->prepare(
                "SELECT * FROM jobs WHERE id = :id_job " .
                " AND password = :password "
        );

        $params = array(
                ':id_job'   => $id,
                ':password' => $password
        );

        $stmt->execute( $params );

        $stmt->setFetchMode( PDO::FETCH_CLASS, 'Chunks_ChunkStruct' );
        $fetched = $stmt->fetch();

        if ( $fetched == false ) {
            throw new Exceptions\NotFoundError('Record not found');
        } else {
            return $fetched;
        }
    }

    /**
     * @param Translations_SegmentTranslationStruct $translation
     *
     * @return Chunks_ChunkStruct
     */
    public static function getBySegmentTranslation( Translations_SegmentTranslationStruct $translation ) {
        $sql = "select * from jobs where id = :id_job
           AND jobs.job_first_segment <= :id_segment
           AND jobs.job_last_segment >= :id_segment ";

        $conn = Database::obtain()->getConnection();
        $stmt = $conn->prepare( $sql );
        $stmt->execute(array(
                'id_job' => $translation->id_job,
                'id_segment' => $translation->id_segment
        ));
        $stmt->setFetchMode( PDO::FETCH_CLASS, 'Chunks_ChunkStruct' );
        return $stmt->fetch();
    }

    /**
     * @param $id_job
     * @return Chunks_ChunkStruct[]
     */
    public static function getByJobID( $id_job ) {
        $conn = Database::obtain()->getConnection();
        $stmt = $conn->prepare("SELECT * FROM " .
                "jobs WHERE id = ?
                order by job_first_segment asc");

        $stmt->execute(array( $id_job ));
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Chunks_ChunkStruct');
        return $stmt->fetchAll( ) ;
    }

    /**
     * @param $id_project
     *
     * @return Chunks_ChunkStruct[]
     */
    public function getByProjectID( $id_project ) {
        $conn = $this->con->getConnection();
        $stmt = $conn->prepare("SELECT * FROM " .
            "jobs WHERE id_project = ? ORDER BY job_first_segment ");

        $stmt->execute(array( $id_project ));
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Chunks_ChunkStruct');
        return $stmt->fetchAll( ) ;
    }

    /**
     * @param $id_project
     * @param $id_job
     *
     * @return Chunks_ChunkStruct[]
     */
    public static function getByJobIdProjectAndIdJob( $id_project, $id_job ) {
        $conn = Database::obtain()->getConnection();
        $stmt = $conn->prepare("SELECT * FROM " .
            "jobs WHERE id_project = :id_project AND id = :id_job");

        $stmt->execute(array( 'id_project' => $id_project, 'id_job' => $id_job ));
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Chunks_ChunkStruct');
        return $stmt->fetchAll( ) ;
    }

    protected function _buildResult( $array_result ) {
        // TODO
         $result = array();
         foreach($array_result as $record) {
             $result[] = new Chunks_ChunkStruct( $record );
         }

        // return $result;
    }

}
