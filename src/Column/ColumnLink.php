<?php

/**
 * @copyright   Copyright (c) 2015 Giant.cz <help@giant.cz>
 * @author      Pavel Janda <pavel.janda@giant.cz>
 * @package     Giant
 */

namespace Ublaboo\DataGrid\Column;

use Nette\Utils\Html;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\Row;
use Ublaboo\DataGrid\Exception\DataGridHasToBeAttachedToPresenterComponentException;
use Ublaboo\DataGrid\Exception\DataGridColumnRendererException;

class ColumnLink extends Column
{

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $class;

	/**
	 * @var array
	 */
	protected $params;

	/**
	 * @var string
	 */
	protected $href;

	/**
	 * @var string
	 */
	protected $icon;

	/**
	 * @var array
	 */
	protected $data_attributes = [];


	/**
	 * @param DataGrid $grid
	 * @param string $key
	 * @param string $column
	 * @param string $name
	 * @param string $href
	 * @param array  $params
	 */
	public function __construct(DataGrid $grid, $key, $column, $name, $href, $params)
	{
		parent::__construct($grid, $key, $column, $name);

		$this->href   = $href;
		$this->params = $params;
	}


	/**
	 * Render row item into template
	 * @param  Row   $row
	 * @return mixed
	 */
	public function render(Row $row)
	{
		/**
		 * Renderer function may be used
		 */
		try {
			return $this->useRenderer($row);
		} catch (DataGridColumnRendererException $e) {
			/**
			 * Do not use renderer
			 */
		}

		$value = parent::render($row);

		$a = Html::el('a')
			->href($this->createLink($this->href, $this->getItemParams($row, $this->params)))
			->setText($value);

		if ($this->title) { $a->title($this->title); }
		if ($this->class) { $a->class($this->class); }
		
		$element = $a;
		
		if($this->icon != NULL) {
			$emptyEl = Html::el('span');

			$span = Html::el('span')->addAttributes(array("class" => $this->icon));
			$span->setHtml("&nbsp");

			$emptyEl->add($span);
			$emptyEl->add($a);

			$element = $emptyEl;
		}

		return $element;
	}


	/**
	 * Set icon before simple link
	 * @param string $class
	 * @return ColumnLink
	 */
	public function setIcon($class = NULL)
	{
		$this->icon = $class;

		return $this;
	}


	/**
	 * Setting data attributes
	 * @param string $key
	 * @param mixed $value
	 * @return static
	 */
	public function setDataAttribute($key, $value)
	{
		$this->data_attributes[$key] = $value;

		return $this;
	}


	/**
	 * Set attribute title
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}


	/**
	 * Get attribute title
	 */
	public function getTitle()
	{
		return $this->title;
	}


	/**
	 * Set attribute class
	 * @param string $class
	 */
	public function setClass($class)
	{
		$this->class = $class;

		return $this;
	}


	/**
	 * Get attribute class
	 */
	public function getClass()
	{
		return $this->class;
	}

}
