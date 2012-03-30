<?php



abstract class Page {
	
	/**
	 * @var String nom du script actuel, exemple 'index'
	 */
	private $name;
	
	/**
	 * @var String sous-titre de la page
	 */
	public $title=null;
	
	public $css_files=array();
	public $js_files=array();
	
	
	/**
	 * 
	 * @var Array associatif contenant les variables du template, $page->key=$value
	 */
	private $template_vars=array();
	
	
	/**
	 * 
	 * @var String nom du gabarit, par default le nom de cette page + '.tpl'
	 */
	private $gabarit=null;
	
	
	private $ajax=false;

	
	public function __construct() {
		$this->name=strtolower(basename(get_class($this), '.php'));
		
		$this->ajax=isValue('ajax');
		
		$this->init();
	}
	
	
	private function init() {
		$this->gabarit=new Standard($this);
		$this->setTitle();
		
		$this->addJs(WWW_JS.'jquery-1.7.1.min.js');
		$this->addJs(WWW_JS.'jquery.ba-hashchange.min.js');
		$this->addJs(WWW_JS.'main.js');
	}
	
	
	public final function __set($key, $value) {
		$this->template_vars[$key]=$value;
	}
	public final function __get($key) {
		return $this->template_vars[$key];
	}
	
	
	public function getName() {
		return $this->name;
	}
	
	
	
	public function setTitle($title=null) {
		if(is_null($title))
			$this->title=SITE_NAME;
		else
			$this->title=SITE_NAME.' - '.$title;
	}
	
	public function addCss($css_file) {
		$this->css_files[]=$css_file;
	}
	public function addJs($js_file) {
		$this->js_files[]=$js_file;
	}
	
	
	
	protected abstract function process();
	
	public function fetch() {
		$this->gabarit->process();
		$this->process();
		
		smarty()->assign('page', $this);
		
		smarty()->assign($this->template_vars);
		
		return smarty()->fetch(DIR_TPL.$this->name.'.tpl');
	}
	
	public function run() {
		if($this->ajax)
			print $this->fetch();
		else
			$this->gabarit->display();
	}
	
	/**
	 * 
	 * Retourne lien de page.
	 * @param String $page page.php, ou mettre jeu:tictactoe pour rediriger vers un jeu.
	 * @return String lien absolu vers la page.
	 */
	public static function getPageLink($page) {
		return WWW_ROOT.$page;
	}
	
}