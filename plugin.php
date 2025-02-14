<?php
/*
	@Package: Bludit
	@Plugin: Cookie Consent
	@Version: 1.1
	@Author: Fred K.
	@Realised: 10 August 2015
	@Updated: 05 November 2018
*/
class pluginCookieConsent extends Plugin {
	private $loadWhenController = array(
		'configure-plugin'
	);
	// Plugin datas
	public function init()
	{
		$this->dbFields = array(
			'enable' => true,
			'message' => 'Ce site utilise des cookies, notamment pour les statistiques de visites. Le fait de continuer à naviguer implique votre accord tacite.',
			'dismiss' => 'Fermer l’avertissement',
			'learnMore' => 'En savoir plus',
			'link' => 'http://silktide.com/cookieconsent',
			'theme' => 'dark-floating',
			'adblock' => false,
			'adblock_message' => '<h4>Il semblerait que vous utilisez Adblock, ou un autre logiciel destiné à bloquer les publicités.</h4>Quand vous cliquez sur une bannière de pub, c’est aussi remercier l’auteur du site pour les articles que vous lisez.<br />Si vous pensez que tout travail mérite récompense, alors n’hésitez pas à faire un bon geste avec un petit clic. Merci.',
			'adblock_background_color' => '146FC2'
			);
	}
	public function adminHead()
	{
		global $layout;
		$html = '';
		if(in_array($layout['controller'], $this->loadWhenController))
		{
			$pluginPath = $this->htmlPath();
			$html .= '<script src="' .$pluginPath. 'jscolor/jscolor.js"></script>'.PHP_EOL;
			return $html;
		}
	}
	// Backend configuration page
	public function form()
	{
		global $L, $site;
		$pluginPath = $this->htmlPath(). 'configurator-themes/';

        echo "Echo 1";
        echo $pluginPath;
        echo $this->getValue('message');

		$html  = '<div>';
		$html .= '<label>'.$L->get('Enable plugin (Config save)').'</label>';
		$html .= '<select name="enable">';
		$html .= '<option value="true" '.($this->getValue('enable')?'selected':'').'>'.$L->get('Enabled').'</option>';
		$html .= '<option value="false" '.(!$this->getValue('enable')?'selected':'').'>'.$L->get('Disabled').'</option>';
		$html .= '</select>';
		$html .= '</div>';

		$html .= '<div' .($this->getValue('enable') ? '':' style="display:none"'). '>';
		$html .= '<label for="theme">'.$L->get('Choose theme'). '</label>';

	    $html .= '<div class="d-flex flex-wrap">';
		$html .= '<figure style="margin-right:10px"><img src="'.$pluginPath. 'dark-bottom.png" alt="dark-bottom" style="border: solid 1px #212529;width:150px" /> <figcaption class="uk-thumbnail-caption">' .$L->get('Dark Bottom'). '</figcaption></figure>';
		$html .= '<figure style="margin-right:10px"><img src="'.$pluginPath. 'dark-floating.png" alt="dark-floating" style="border: solid 1px #212529;width:150px" /> <figcaption class="uk-thumbnail-caption">' .$L->get('Dark Floating'). '</figcaption></figure>';
		$html .= '<figure style="margin-right:10px"><img src="'.$pluginPath. 'dark-top.png" alt="dark-top" style="border: solid 1px #212529;width:150px" /> <figcaption class="uk-thumbnail-caption">' .$L->get('Dark Top'). '</figcaption></figure>';
		$html .= '<figure style="margin-right:10px"><img src="'.$pluginPath. 'light-bottom.png" alt="light-bottom" style="border: solid 1px #212529;width:150px" /> <figcaption class="uk-thumbnail-caption">' .$L->get('Light Bottom'). '</figcaption></figure>';
		$html .= '<figure style="margin-right:10px"><img src="'.$pluginPath. 'light-floating.png" alt="light-floating" style="border: solid 1px #212529;width:150px" /> <figcaption class="uk-thumbnail-caption">' .$L->get('Light Floating'). '</figcaption></figure>';
		$html .= '<figure style="margin-right:10px"><img src="'.$pluginPath. 'light-top.png" alt="light-top" style="border: solid 1px #212529;width:150px" /> <figcaption class="uk-thumbnail-caption">' .$L->get('Light Top'). '</figcaption></figure>';
		$html .= '</div>';

	    $html .= '<select name="theme">';
	    $themeOptions = array('dark-bottom' => $L->get('Dark Bottom'),'dark-floating' => $L->get('Dark Floating'),'dark-top' => $L->get('Dark Top'),'light-bottom' => $L->get('Light Bottom'),'light-floating' => $L->get('Light Floating'),'light-top' => $L->get('Light Top'));
	        foreach($themeOptions as $text=>$value)
	    $html .= '<option value="'.$text.'"'.( ($this->getValue('theme')===$text)?' selected="selected"':'').'>'.$value.'</option>';
	    $html .= '</select>';
		$html .= '</div>';

		$html .= '<div' .($this->getValue('enable') ? '':' style="display:none"'). '>';
		$html .= '<label for="message">'.$L->get('Headline text'). '</label>';
		$html .= '<textarea name="message" id="jsmessage" rows="3">'.$this->getValue('message').'</textarea>';
		$html .= '</div>';


		$html .= '<div' .($this->getValue('enable') ? '':' style="display:none"'). '>';
		$html .= '<label for="dismiss">'.$L->get('Accept button text'). '</label>';
		$html .= '<input type="text" name="dismiss" id="jsdismiss" value="'.$this->getValue('dismiss').'" />';
		$html .= '</div>';

		$html .= '<div' .($this->getValue('enable') ? '':' style="display:none"'). '>';
		$html .= '<label for="learnMore">'.$L->get('Read more button text'). '</label>';
		$html .= '<input type="text" name="learnMore" id="jslearnMore" value="'.$this->getValue('learnMore').'" />';
		$html .= '</div>';

		$html .= '<div' .($this->getValue('enable') ? '':' style="display:none"'). '>';
		$html .= '<label for="link">'.$L->get('Your cookie policy'). '</label>';
		$html .= '<input type="url" name="link" id="jslink" class="uk-form-large uk-form-width-large" value="'.$this->getValue('link').'" />';
		$html .= '<div class="uk-form-help-block">'.$L->get('If you already have a cookie policy, link to it here.'). '</div>';
		$html .= '</div>';
		$html .= '<div>&nbsp;</div>';

		/*	Detect AdBlock Part */
		$html .= '<h2>' .$L->get('adblock-config'). '</h2>';
		$html .= '<div>';
		$html .= '<label>'.$L->get('ad-blocker-detection-plugin').'</label>';
		$html .= '<select name="adblock">';
		$html .= '<option value="true" '.($this->getValue('adblock')?'selected':'').'>'.$L->get('Enabled').'</option>';
		$html .= '<option value="false" '.(!$this->getValue('adblock')?'selected':'').'>'.$L->get('Disabled').'</option>';
		$html .= '</select>';
		$html .= '</div>';

		$html .= '<div' .($this->getValue('adblock') ? '':' style="display:none"'). '>';
		$html .= '<label for="adblock_background_color">'.$L->get('adblock_background_color'). '</label>';
		$html .= '<input type="text" class="color" name="adblock_background_color" id="jsadblock_background_color" value="'.$this->getValue('adblock_background_color').'" />';
		$html .= '</div>';

		$html .= '<div' .($this->getValue('adblock') ? '':' style="display:none"'). '>';
		$html .= '<label for="adblock_message">'.$L->get('adblock_message'). '</label>';
		$html .= '<textarea name="adblock_message" id="jsadblock_message" rows="5">'.$this->getValue('adblock_message').'</textarea>';
		$html .= '</div>';

		return $html;
	}
	// Show in Public theme head
	public function siteHead()
	{
		global $Site;
		$pluginPath = $this->htmlPath() .'configurator-themes/';
		$html = ''.PHP_EOL;

		if($this->getValue('enable')) {
			$html .= PHP_EOL.'<!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent -->'.PHP_EOL;
			$html .= '<script type="text/javascript">'.PHP_EOL;
			$html .= 'window.cookieconsent_options = {"message":"'.$this->getValue('message').'","dismiss":"'.$this->getValue('dismiss').'","learnMore":"'.$this->getValue('learnMore').'","link":"'.$this->getValue('link').'","theme":"'.$this->getValue('theme').'"};'.PHP_EOL;
			$html .= '</script>'.PHP_EOL;

			$html .= '<script type="text/javascript" src="'.$pluginPath.'cookieconsent.latest.min.js"></script>'.PHP_EOL;
			$html .= '<!-- End Cookie Consent plugin -->'.PHP_EOL;
		}
		// For AdBlock Detect
		if($this->getValue('adblock'))  $html .= '<style type="text/css">#advert-notice { position: fixed; top: 0; left:0; z-index: 10000; width: 100%; padding: 40px 60px 50px; margin: 0; background-color: #'.$this->getValue('adblock_background_color').'; color: #fff; font-size: 17px; text-align: center; display: block;}</style>'.PHP_EOL;
		return $html;
	}
	// Show in Public theme footer
	public function siteBodyEnd()
	{
		// Path plugin.
		$pluginPath = $this->htmlPath();

		$html = ''.PHP_EOL;
		if($this->getValue('adblock')) {
			$html .= '<script src="'.$pluginPath.'js/advert.js"></script>'.PHP_EOL;
			$html .= '<script>
if (document.getElementById("ads") == null) {

    document.write("<div id=\'advert-notice\'>'.Sanitize::htmlDecode($this->getValue('adblock_message')).'</div>");

}
</script>'.PHP_EOL;
		}
		return $html;
	}

}
