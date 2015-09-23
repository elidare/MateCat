<?php

/**
 * TODO: so far it's clear that this decorator is based on the job, so the job should
 * so the job should be the argument to the constructor.
 */

class HeaderDecorator {

    private $controller ;
    private $job ;
    private $job_stats ;

    public $mainButtonLabel ;
    public $mainButtonLabelReverse ;
    public $mainButtonId ;
    public $mainButtonProgressLabel ;

    const COMPLETED = 'SENT';
    const MARK_AS_COMPLETE = 'SEND';
    const DOWNLOAD_TRANSLATION = 'Download translation';

    const PREVIEW = 'Preview';
    const DOWNLOADING = 'Downloading...';
    const SAVING = 'Sending...';

    public function __construct( $controller ) {
      $this->controller = $controller;
      $this->job_stats = $controller->getJobStats();
      $this->job = $controller->getJob();

      $this->evalProperties();
    }

    public function downloadStatus() {
      return $this->job_stats['DOWNLOAD_STATUS'] ;
    }

    private function evalProperties() {
      if ( $this->job->isFeatureEnabled( Features::PROJECT_COMPLETION ) ) {
        $this->evalForProjectCompletion();
      } else {
        $this->evalForDefault();
      }
    }

    private function evalForProjectCompletion() {
      $this->mainButtonId = 'markAsCompleteButton' ;

      if ( $this->job->isMarkedComplete() ) {
        $this->mainButtonLabel        = self::COMPLETED ;
        $this->mainButtonLabelReverse = self::MARK_AS_COMPLETE ;
      }
      else {
        $this->mainButtonLabel        = self::MARK_AS_COMPLETE ;
        $this->mainButtonLabelReverse = self::COMPLETED ;
      }
    }

    private function evalForDefault() {
      $this->mainButtonId = 'downloadProject';

      if ( $this->isDownloadable() ) {
        $this->mainButtonLabel        = self::DOWNLOAD_TRANSLATION ;
        $this->mainButtonLabelReverse = self::PREVIEW ;
      }
      else {
        $this->mainButtonLabel        = self::PREVIEW ;
        $this->mainButtonLabelReverse = self::DOWNLOAD_TRANSLATION  ;
      }
    }

    // TODO the job itself should know about this
    private function isDownloadable() {
      return  $this->job_stats['TODO_FORMATTED'] == 0 && $this->job_stats['ANALYSIS_COMPLETE'] ;
    }

}
