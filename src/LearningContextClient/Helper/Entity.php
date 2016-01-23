<?PHP
namespace LearningContextClient\Helper;

class Entity {

	private $key;
	private $value;

	function __construct($key, $value) {
		$this->key = $key;
		$this->value = $value;
	}
	
	public function getKey() {
		return $this->key;
	}

	public function setKey($key) {
		$this->key = $key;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		$this->value = $value;
	}

}