<?php
namespace Perguntados;

class Opponent extends Base {
	private $username;
	private $facebook_id;
	private $facebook_name;
	private $isFriend;

	public function __construct(array $opponent = NULL)
	{
		parent::__construct();
		if ($opponent != NULL) {
			$this->username = $opponent['username'];
			$this->facebook_id = $opponent['facebook_id'];
			$this->facebook_name = $opponent['facebook_name'];
			$this->id = (string)$opponent['id'];
			$this->isFriend = $opponent['is_friend'];
			$this->toArray = [
				'id' => $this->id,
				'username' => $this->username,
				'facebook_id' => $this->facebook_id,
				'facebook_name' => $this->facebook_name,
				'isFriend' => $this->isFriend
			];
		}
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @return string
	 */
	public function getFacebookId()
	{
		return $this->facebook_id;
	}

	/**
	 * @return string
	 */
	public function getFacebookName()
	{
		return $this->facebook_name;
	}

	/**
	 * @return boolean
	 */
	public function getIsFriend()
	{
		return $this->isFriend;
	}
}