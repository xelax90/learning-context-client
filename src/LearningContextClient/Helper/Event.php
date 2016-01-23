<?PHP
namespace LearningContextClient\Helper;

class Event {

	private $action;
	private $created_at;
	private $platform;
	private $type;
	private $minor;
	private $session;
	private $entities = array();
	private $interests = array();

	function __construct($action, $created_at, $platform, $type, $minor, $session="") {
		$this->action = $action;
		$this->created_at = $created_at;
		$this->platform = $platform;
		$this->type = $type;
		$this->minor = $minor;
		$this->session = $session;
	}
   
	public function getAction() {
		return $this->action;
	}

	public function setAction($action) {
		$this->action = $action;
	}

	public function getCreatedAt() {
		return $this->created_at;
	}

	public function setCreatedAt($created_at) {
		$this->created_at = $created_at;
	}

	public function getPlatform() {
		return $this->platform;
	}

	public function setPlatform($platform) {
		$this->platform = $platform;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function getMinor() {
		return $this->minor;
	}

	public function setMinor($minor) {
		$this->minor = $minor;
	}

	public function getSession() {
		return $this->session;
	}

	public function setSession($session) {
		$this->session = $session;
	}

	public function getEntities() {
		return $this->entities;
	}

	public function addEntity($entity) {
		$this->entities[] = $entity;
	}

	public function getInterests() {
		return $this->interests;
	}

	public function addInterest($interest) {
		$this->interests[] = $interest;
	}

	public function toJson(){
        $var = get_object_vars($this);
		$data = array();
        foreach($var as $key=>$value){
			if ($key == "entities") {
				foreach ($value as $k=>$v) {
					if(is_object($v)){
						$data['entities'][] = array("key" => $v->getKey(), "value" => $v->getValue());
					}
				}
			} else if ($key == "type") {
				$data['category'][$key] = $value;
			} else if ($key == "minor") {
				$data['category'][$key] = $value;
			} else {
				$data[$key] = $value;
			}
        }
        return json_encode($data);
     }	
}