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
     * You can access database and application objects and parameters via $this->db,
     * $this->app and $this->params respectively
    */

    // Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer')
		{
			return true;
		}

		// Simple performance check to determine whether bot should process further
		if (strpos($article->text, '{loadcards}') === false)
		{
			return true;
		}

    $filesanddirs = scandir('images/cards');

    $markup = '<img class="catalog-banner" src="images/catalog-eoy-banner.jpg" alt="catalog fin d\'année banner">';
    $cardMarkupTamplate = '<div
class="card">
  <a href="images/cards/cardname" class="card-choose"><img src="images/cards/cardname" alt="cardname"></a>
  <div class="card-title">
    <span>cardname</span>
  </div>
</div>';
    for ($i=0; $i < count($filesanddirs) - 2; $i++) {
      $markup.= str_replace('cardname', $filesanddirs[$i+2], $cardMarkupTamplate);
    }

    

    $article->text = str_replace('{loadcards}', $markup, $article->text);

    return true;
  }
}
