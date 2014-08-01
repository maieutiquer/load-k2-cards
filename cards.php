<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgContentCards extends JPlugin
{
  protected $autoloadLanguage = true;

  /**
   * Plugin method with the same name as the event will be called automatically.
   */
  function onContentPrepare($context, &$article, &$params, $page = 0)
  {
    /*
     * Plugin code goes here.
     * You can access database and application objects and parameters via $this->db,
     * $this->app and $this->params respectively
    */

    // Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer')
		{
			return true;
		}

		// Simple performance check to determine whether bot should process further
		if (strpos($article->text, 'loadcards') === false)
		{
			return true;
		}

    // Check if the article contains {loadcards}
		$loadCards = strpos($article->text, '{loadcards}');

    

    

    return true;
  }
}
