<?php
namespace Perguntados;


class Spin extends Base {

	private $type;
	private $question;

	/**
	 * @param array $spin
	 * @return Spin $this
	 */
	public function __construct(array $spin = NULL)
	{
		parent::__construct();
		if ($spin != NULL) {
			$this->type = (string)$spin['type'];
			$this->question = new Question($spin['questions'][0]['question']);
			$this->toArray = [
				'type' => $this->type,
				'question' => $this->question->toArray()
			];
		}
		return $this;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return (string)$this->type;
	}

	/**
	 * @return Question
	 */
	public function getQuestions()
	{
		return $this->question;
	}
}