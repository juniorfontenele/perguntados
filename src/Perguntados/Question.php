<?php
namespace Perguntados;


class Question extends Base {

	private $category;
	private $text;
	private $answers;
	private $correctAnswer;

	/**
	 * @param array $question
	 * @return Question $this
	 */
	public function __construct(array $question = NULL)
	{
		parent::__construct();
		if ($question != NULL) {
			$this->id = (string)$question['id'];
			$this->category = (string)$question['category'];
			$this->text = (string)$question['text'];
			$this->answers = $question['answers'];
			$this->correctAnswer = (int)$question['correct_answer'];
			$this->toArray = [
				'id' => $this->id,
				'category' => $this->category,
				'text' => $this->text,
				'answers' => $this->answers,
				'correctAnswer' => $this->correctAnswer
			];
		}
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCorrectAnswer()
	{
		return (int)$this->correctAnswer;
	}

	/**
	 * @return string
	 */
	public function getText()
	{
		return (string)$this->text;
	}

	/**
	 * @return array
	 */
	public function getAnswers()
	{
		return $this->answers;
	}

	/**
	 * @return string
	 */
	public function getCategory()
	{
		return (string)$this->category;
	}

	public function getQuestionId()
	{
		return (string)$this->id;
	}
}