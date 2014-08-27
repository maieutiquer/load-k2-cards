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

    // define cards folder
    $cardsDir = 'images/cards';

    // begin markup
    $markup = '';

    // show banner
    // TODO: move banner elsewhere, should not be part of plugin code
    $markup.= '<img class="catalog-banner" src="images/catalog-eoy-banner.png" alt="catalog fin d\'année banner">';

    /**
     * add all cards to markup code
     */
    function getCards($cardsDir, &$markup) {
      $filesanddirs = scandir($cardsDir);
      // this template will be taken for each card and cardname will be replaced
      $cardMarkupTamplate = '<div class="card">
  <a href="shop/cartes/personnalisation/?card=cardname" class="card-choose" data-card="cardname"><img src="'.$cardsDir.'/cardname.ext" alt="cardname"></a>
  <div class="card-title"><span>cardname</span></div>
</div>';
      for ($i=0; $i < count($filesanddirs) - 2; $i++)
      {
        $filename = $filesanddirs[$i+2];
        if (!is_dir($cardsDir.'/'.$filename)) {
          $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
          $cardMarkup = str_replace('cardname.ext', $filename, $cardMarkupTamplate);
          $cardMarkup = str_replace('cardname', $withoutExt, $cardMarkup);
          $markup.= $cardMarkup;
        }
      }
    }

    getCards($cardsDir, $markup);

    $artistsDir = 'images/artists';
    $artistFilesAndDirs = scandir($artistsDir);
    for ($i=0; $i < count($artistFilesAndDirs) - 2; $i++) 
    {
      $dirname = $artistFilesAndDirs[$i+2];
      if (is_dir($artistsDir.'/'.$dirname))
      {
        $artistName = str_replace('_', ' ', $dirname);
        $markup.= '<span class="artist-anchor" id="'.$dirname.'"></span>';
        $artistCardsDir = $artistsDir.'/'.$dirname.'/cards';
        getCards($artistCardsDir, $markup);
      }
    }

    // in the article, replace plugin call with cards markup
    $article->text = str_replace('{loadcards}', $markup, $article->text);

    return true;
  }

}
