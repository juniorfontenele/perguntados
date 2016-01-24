<?php
namespace Perguntados;


use Carbon\Carbon;

class DuelGame extends Base {
	private $language;
	private $type;
	private $countdown;
	private $status;
	private $createdAt;
	private $expireAt;
	private $myTurn;

	/**
	 * @param array $duelGame
	 * @return DuelGame
	 */
	public function __construct(array $duelGame = NULL)
	{
		if ($duelGame != NULL) {
			$this->id = (string)$duelGame['id'];
			$this->language = (string)$duelGame['language'];
			$this->type = (string)$duelGame['type'];
			$this->countdown = (int)$duelGame['countdown'];
		}
		$this->status = "";
		$this->createdAt = new Carbon();
		$this->expireAt = new Carbon();
		$this->myTurn = false;
		$this->toArray = [
			'id' => $this->id,
			'language' => $this->language,
			'type' => $this->type,
			'countdown' => $this->countdown,
			'status' => $this->status,
			'createdAt' => $this->createdAt,
			'expireAt' => $this->expireAt,
			'myTurn' => $this->myTurn
		];
		return $this;
	}

	/**
	 * @param Carbon $createdAt
	 * @return DuelGame
	 */
	public function setCreatedAt(Carbon $createdAt)
	{
		$this->createdAt = $createdAt;
		return $this;
	}

	/**
	 * @param Carbon $expireAt
	 * @return DuelGame
	 */
	public function setExpireAt(Carbon $expireAt)
	{
		$this->expireAt = $expireAt;
		return $this;
	}

	/**
	 * @param boolean $myTurn
	 * @return DuelGame
	 */
	public function setMyTurn($myTurn)
	{
		$this->myTurn = $myTurn;
		return $this;
	}

	/**
	 * @param string $status
	 * @return DuelGame
	 */
	public function setStatus($status)
	{
		$this->status = $status;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCountdown()
	{
		return $this->countdown;
	}

	/**
	 * @return string
	 */
	public function getLanguage()
	{
		return $this->language;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}
} 