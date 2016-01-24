<?php
namespace Perguntados;

use Carbon\Carbon;

/**
 * Class Game
 * @package Perguntados
 */
class Game  extends Base {
	private $opponent;
	private $status;
	private $language;
	private $createdAt;
	private $lastTurn;
	private $expireAt;
	private $playerNumber;
	private $myCrowns;
	private $opponentCrowns;
	private $roundNumber;
	private $spin;
	private $myTurn;

	/**
	 * @param array $game
	 * @throws \Exception
	 */
	public function __construct(array $game = NULL)
	{
		parent::__construct();
		if ($game == NULL) {
			throw new \Exception("Parameter 'game' not set");
		}
		return $this->updateGame($game);
	}

	/**
	 * @param array $game
	 * @return Game
	 * @throws \Exception
	 */
	public function updateGame(array $game = NULL)
	{
		if ($game ==  NULL) {
			throw new \Exception("Parameter 'game' not set");
		}
		$this->id = (string)$game['id'];
		$this->opponent = new Opponent($game['opponent']);
		$this->status = $game['game_status'];
		$this->language = $game['language'];
		$this->createdAt = new Carbon($game['created']);
		$this->lastTurn = new Carbon($game['last_turn']);
		$this->expireAt = new Carbon($game['expiration_date']);
		$this->playerNumber = $game['my_player_number'];
		$this->roundNumber = $game['round_number'];
		$this->myTurn = $game['my_turn'];
		if ($this->playerNumber == 1) {
			$this->myCrowns = array_key_exists('crowns',$game['player_one']) ? $game['player_one']['crowns'] : [];
			$this->opponentCrowns = array_key_exists('crowns',$game['player_two']) ? $game['player_two']['crowns'] : [];
		}
		else {
			$this->myCrowns = array_key_exists('crowns',$game['player_two']) ? $game['player_two']['crowns'] : [];
			$this->opponentCrowns = array_key_exists('crowns',$game['player_one']) ? $game['player_one']['crowns'] : [];
		}
		if (array_key_exists('spins_data',$game)) {
			$this->spin = new Spin($game['spins_data']['spins'][0]);
		}
		$this->toArray = [
			'id' => $this->id,
			'oponnent' => $this->opponent->toArray(),
			'status' => $this->status,
			'language' => $this->language,
			'createdAt' => $this->createdAt->format('d/m/Y H:i:s'),
			'expireAt' => $this->expireAt->format('d/m/Y H:i:s'),
			'lastTurn' => $this->lastTurn->format('d/m/Y H:i:s'),
			'myTurn' => $this->myTurn,
			'playerNummber' => $this->playerNumber,
			'roundNumber' => $this->roundNumber,
			'myCrowns' => $this->myCrowns,
			'opponentCrowns' => $this->opponentCrowns,
			'spins' => $this->spin->toArray()
		];
		return $this;
	}

	/**
	 * @return Carbon
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	/**
	 * @return Carbon
	 */
	public function getExpireAt()
	{
		return $this->expireAt;
	}

	/**
	 * @return bool
	 */
	public function isExpired()
	{
		return $this->expireAt->isToday() || $this->expireAt->isPast();
	}

	/**
	 * @return array
	 */
	public function getMyCrowns()
	{
		return $this->myCrowns;
	}

	/**
	 * @return Spin
	 */
	public function getSpin()
	{
		return $this->spin;
	}

	public function getGameId()
	{
		return (string)$this->id;
	}

	/**
	 * @return bool
	 */
	public function isActive()
	{
		return (($this->status != "ENDED") && ($this->status != "EXPIRED"));
	}

	/**
	 * @return bool
	 */
	public function isMyTurn()
	{
		return ($this->myTurn);
	}
}