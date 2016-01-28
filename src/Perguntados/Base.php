<?php
namespace Perguntados;

use Dotenv\Dotenv;

class Base {
	protected $id;
	protected $toArray;

	public function __construct()
	{
		$conf = new Dotenv(__DIR__.'/../../');
		$conf->load();
		return $this;
	}

	/**
	 * @param string $id
	 * @return string
	 */
	public function id($id = NULL)
	{
		if ($id != NULL) {
			$this->id = (string)$id;
		}
		return (string)$id;
	}

	/**
	 * @param string $id
	 * @return mixed $this
	 */
	public function setId($id)
	{
		$this->id = (string)$id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return (string)$this->id;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return $this->toArray;
	}

	/**
	 * @return string
	 */
	public function toJson()
	{
		return json_encode($this->toArray);
	}
} 