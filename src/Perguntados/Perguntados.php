<?php
namespace Perguntados;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;

class Perguntados extends Base {

	private $url;
	private $userId;
	private $headers;
	private $conn;
	private $guzzleConf;
	private $dash;
	private $maxLives;
	private $lives;
	private $coins;
	private $extraShots;
	private $games;
	private $nextIncrement;

	/**
	 * @return Perguntados $this
	 */
	public function __construct()
	{
		parent::__construct();
		$this->dash = [];
		$this->maxLives = (int)0;
		$this->lives = (int)0;
		$this->nextIncrement = (int)0;
		$this->coins = (int)0;
		$this->extraShots = (int)0;
		$this->games = [];
		$this->url = getenv('APP_URL');
		$this->userId = getenv('USER_ID');
		$this->headers = [
			'Cookie' => 'ap_session='.getenv('APP_COOKIE'),
			'Eter-Agent' => '1|iOS-AppStr|iPhone7,2|0|iOS 9.1|0|2.3.0.1|pt-BR|pt-BR|BR|1',
			'Connection' => 'keep-alive',
			'Accept' => '*/*',
			'Accept-Language' => 'pt-br'
		];
		$this->guzzleConf = [
			'base_uri' => $this->url,
			'cookies' => false,
			'verify' => false,
			'exceptions' => false,
			'allow_redirects' => true,
			'debug' => false,
			'headers' => $this->headers
		];
		$this->conn = new Client($this->guzzleConf);
		return $this->_connect();
	}

	/**
	 * Connect and get dashboard statistics
	 * @return Perguntados
	 * @throws Exception
	 */
	private function _connect()
	{
		$res = $this->conn->request('GET', 'api/users/'.$this->userId.'/dashboard?');
		if ($res->getStatusCode() != '200') {
			throw new Exception("Can't get dashboard statistics");
		}
		$json = $res->getBody();
		$this->dash = json_decode($json,true);
		$this->maxLives = $this->dash['lives']['max'];
		$this->lives = $this->dash['lives']['quantity'];
		$this->nextIncrement = array_key_exists('next_increment',$this->dash['lives']) ? $this->dash['lives']['next_increment'] : (int)0;
		$this->coins = $this->dash['coins'];
		$this->extraShots = $this->dash['extra_shots'];
		$this->games = [];
		foreach ($this->dash['list'] as $game) {
			if (($game['game_status'] != "ENDED") && ($game['game_status'] != "EXPIRED") && ($game['my_turn'])){
				$gameId = (string)$game['id'];
				$this->games[$gameId] = new Game($game);
			}
		}
		$this->toArray = $this->dash;
		return $this;
	}

	/**
	 * Update statistics
	 * @return Perguntados
	 * @throws Exception
	 */
	public function update()
	{
		return $this->_connect();
	}

	/**
	 * Returns Dashboard Statistics in JSON String
	 * @return string JSON String
	 */
	public function getJsonDash()
	{
		return json_encode($this->dash);
	}

	/**
	 * Returns Dashboard Statistics
	 * @return array
	 */
	public function getDash()
	{
		return $this->dash;
	}

	/**
	 * Returns active games
	 * @return array
	 */
	public function getGames()
	{
		return $this->games;
	}

	/**
	 * Returns an instance of game
	 * @param string $gameId
	 * @throws Exception
	 * @return Game
	 */
	public function getGame($gameId = NULL)
	{
		if (!array_key_exists($gameId,$this->games)) {
			throw new Exception("Game not found");
		}
		return $this->games[$gameId];
	}

	/**
	 * @return int
	 */
	public function countActiveGames()
	{
		return count($this->games);
	}

	/**
	 * @param Game $game
	 * @throws Exception
	 * @return Game
	 */
	public function answerQuestion(Game &$game = NULL)
	{
		if (!$game) {
			throw new Exception('Parameter \'game\' not set');
		}
		$post = [
			'answers' => [[
				'id' => $game->getSpin()->getQuestions()->getQuestionId(),
				'category' => $game->getSpin()->getQuestions()->getCategory(),
				'answer' => $game->getSpin()->getQuestions()->getCorrectAnswer()
			]],
			'type' => $game->getSpin()->getType()
		];
		$res = $this->conn->request('POST', 'api/users/'.$this->userId.'/games/'.$game->getGameId().'/answers', ['json' => $post]);
		if ($res->getStatusCode() != '200') {
			throw new Exception("Can't answer question");
		}
		return $game->updateGame(json_decode($res->getBody(),true));
	}

	/**
	 * Create a new game
	 * @param Opponent $opponent
	 * @param string $language
	 * @return bool|Game
	 * @throws Exception
	 */
	public function createGame(Opponent $opponent = NULL, $language = 'PT')
	{
		if ($this->lives <= 0) {
			throw new Exception('Not enough lives. Wait '.$this->nextIncrement.' seconds.');
		}
		if ($opponent != NULL) {
			$post = [
				'opponent' => [
					'username' => $opponent->getUsername(),
					'id' => $opponent->getId(),
					'facebook_id' => $opponent->getFacebookId()
				],
				'language' => $language
			];
		}
		else {
			$post = [];
		}
		$res = $this->conn->request('POST', 'api/users/'.$this->userId.'/games', ['json' => $post]);
		if ($res->getStatusCode() != '200') {
			return false;
		}
		$json = $res->getBody();
		return new Game(json_decode($json,true));
	}

	/**
	 * Find a new random Duel
	 * @param string $language
	 * @return bool|DuelGame
	 * @throws Exception
	 */
	public function findDuel($language = 'PT')
	{
		if ($this->lives <= 0) {
			throw new Exception('Not enough lives. Wait '.$this->nextIncrement.' segundos.');
		}
		$post = [
			'type' => 'DUEL_GAME',
			'language' => $language
		];
		$res = $this->conn->request('POST', 'api/users/'.$this->userId.'/rooms', ['json' => $post]);
		if ($res->getStatusCode() != '200') {
			throw new Exception("Can't get Duel room");
		}
		$json = $res->getBody();
		return new DuelGame(json_decode($json,true));
	}

	/**
	 * @param DuelGame $game
	 * @return array
	 * @throws Exception
	 */
	public function getDuelQuestions(DuelGame &$game = NULL)
	{
		if ($this->lives <= 0) {
			throw new Exception('Not enough lives. Wait '.$this->nextIncrement.' segundos.');
		}
		if (!$game) {
			throw new Exception("Parameter 'game' is required");
		}
		$res = $this->conn->request('GET', 'api/users/'.$this->userId.'/rooms/'.$game->getId());
		if ($res->getStatusCode() != '200') {
			throw new Exception("Can't get game questions");
		}
		$json = $res->getBody();
		$ret = [];
		$arr = json_decode($json,true);
		$game->setCreatedAt(new Carbon($arr['game']['created']));
		$game->setExpireAt(new Carbon($arr['game']['expiration_date']));
		$game->setStatus($arr['game']['game_status']);
		$game->setMyTurn($arr['game']['my_turn']);
		foreach ($arr['game']['questions'] as $question) {
			$questionId = (string)$question['id'];
			$ret[$questionId] = new Question($question);
		}
		return $ret;
	}

	/**
	 * @param DuelGame $game
	 * @param array $questions
	 * @param int $finishTime
	 * @return bool|Game
	 * @throws Exception
	 */
	public function postDuelAnswers(DuelGame &$game = NULL, array $questions = NULL, $finishTime = 30432)
	{
		if ((!$questions) || (!$game)) {
			throw new Exception("Required parameter not set");
		}
		$post = [];
		$post['finish_time'] = $finishTime;
		$post['answers'] = [];
		foreach ($questions as $question) {
			$answer = [
				'id' => $question->getId(),
				'category' => $question->getCategory(),
				'answer' => $question->getCorrectAnswer()
			];
			$post['answers'][] = $answer;
		}
		$res = $this->conn->request('POST', 'api/users/'.$this->userId.'/games/'.$game->getId().'/answers', ['json' => $post]);
		if ($res->getStatusCode() != '200') {
			throw new Exception("Can't post game answers");
		}
		$json = $res->getBody();
		$arr = json_decode($json,true);
		if (!$arr['my_turn']) {
			$game->setMyTurn(false);
			$game->setStatus('ENDED');
			return $game;
		}
		else {
			return false;
		}
	}

	/**
	 * @param Game $game
	 * @throws Exception
	 * @return Game
	 */
	public function winGame(Game &$game)
	{
		if (!$game->isActive() || !$game->isMyTurn()) {
			throw new Exception("Can't play game");
		}
		while (($game->isActive()) && ($game->isMyTurn())) {
			$this->answerQuestion($game);
		}
		return $game;
	}

	/**
	 * @return bool|Game
	 * @throws Exception
	 */
	public function winRandomDuel()
	{
		$duel = $this->findDuel();
		$questions = $this->getDuelQuestions($duel);
		return $this->postDuelAnswers($duel, $questions);
	}
}