<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Column;

class Actions extends AbstractDisplayer
{
    /**
     * @var array
     */
    protected $appends = [];

    /**
     * @var array
     */
    protected $prepends = [];

    /**
     * Default actions.
     *
     * @var array
     */
    protected $actions = ['view', 'edit', 'delete'];

    /**
     * @var string
     */
    protected $resource;

    public function __construct($value, Grid $grid, Column $column, $row)
    {
        if (!config('admin.actions.show')) {
            $this->disableView();
        }
        if (!config('admin.actions.edit')) {
            $this->disableEdit();
        }
        if (!config('admin.actions.delete')) {
            $this->disableDelete();
        }
        parent::__construct($value, $grid, $column, $row);
    }

    /**
     * Append a action.
     *
     * @param $action
     *
     * @return $this
     */
    public function append($action)
    {
        array_push($this->appends, $action);

        return $this;
    }

    /**
     * Prepend a action.
     *
     * @param $action
     *
     * @return $this
     */
    public function prepend($action)
    {
        array_unshift($this->prepends, $action);

        return $this;
    }

    /**
     * Disable view action.
     *
     * @return $this
     */
    public function disableView()
    {
        array_delete($this->actions, 'view');

        return $this;
    }

    /**
     * Disable delete.
     *
     * @return $this.
     */
    public function disableDelete()
    {
        array_delete($this->actions, 'delete');

        return $this;
    }

    /**
     * Disable edit.
     *
     * @return $this.
     */
    public function disableEdit()
    {
        array_delete($this->actions, 'edit');

        return $this;
    }

    /**
     * Set resource of current resource.
     *
     * @param $resource
     *
     * @return $this
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get resource of current resource.
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource ?: parent::getResource();
    }

    /**
     * {@inheritdoc}
     */
    public function display($callback = null)
    {
        if ($callback instanceof \Closure) {
            $callback->call($this, $this);
        }

        $actions = $this->prepends;

        foreach ($this->actions as $action) {
            /**
             * renderView(); renderEdit(); renderShow();
             */
            $method = 'render' . ucfirst($action);
            array_push($actions, $this->{$method}());
        }

        $actions = array_merge($actions, $this->appends);

        return view('admin::actions.container', [
            'actions' => $actions
        ])->render();
    }

    /**
     * Render view action.
     *
     * @return string
     */
    protected function renderView()
    {
        return view('admin::actions.view', [
            'label' => false,
            'href' => $this->getResource() . '/' . $this->getKey()
        ])->render();
    }

    /**
     * Render edit action.
     *
     * @return string
     */
    protected function renderEdit()
    {
        return view('admin::actions.edit', [
            'label' => false,
            'href' => $this->getResource() . '/' . $this->getKey() . '/edit'
        ])->render();
    }

    /**
     * Render delete action.
     *
     * @return string
     */
    protected function renderDelete()
    {
        return view('admin::actions.delete', [
            'label' => false,
            'urlDelete' => $this->getResource() . '/' . $this->getKey(),
            'callback' => 'reload'
        ])->render();
    }
}
