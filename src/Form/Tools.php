<?php

namespace Encore\Admin\Form;

use Encore\Admin\Facades\Admin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class Tools implements Renderable
{
    /**
     * @var Builder
     */
    protected $form;

    /**
     * Collection of tools.
     *
     * @var array
     */
    protected $tools = ['delete', 'view', 'list'];

    /**
     * Tools should be appends to default tools.
     *
     * @var Collection
     */
    protected $appends;

    /**
     * Tools should be prepends to default tools.
     *
     * @var Collection
     */
    protected $prepends;

    /**
     * Create a new Tools instance.
     *
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        if (!config('admin.actions.show')) {
            $this->disableView();
        }
        if (!config('admin.actions.list')) {
            $this->disableList();
        }
        if (!config('admin.actions.delete')) {
            $this->disableDelete();
        }
        $this->form = $builder;
    }

    /**
     * Append a tools.
     *
     * @param mixed $tool
     *
     * @return $this
     */
    public function append($tool)
    {
        $this->appends->push($tool);

        return $this;
    }

    /**
     * Prepend a tool.
     *
     * @param mixed $tool
     *
     * @return $this
     */
    public function prepend($tool)
    {
        $this->prepends->push($tool);

        return $this;
    }

    /**
     * Disable `list` tool.
     *
     * @return $this
     */
    public function disableList()
    {
        array_delete($this->tools, 'list');

        return $this;
    }

    /**
     * Disable `delete` tool.
     *
     * @return $this
     */
    public function disableDelete()
    {
        array_delete($this->tools, 'delete');

        return $this;
    }

    /**
     * Disable `edit` tool.
     *
     * @return $this
     */
    public function disableView()
    {
        array_delete($this->tools, 'view');

        return $this;
    }

    /**
     * Get request path for resource list.
     *
     * @return string
     */
    protected function getListPath()
    {
        return $this->form->getResource();
    }

    /**
     * Get request path for edit.
     *
     * @return string
     */
    protected function getDeletePath()
    {
        return $this->getViewPath();
    }

    /**
     * Get request path for delete.
     *
     * @return string
     */
    protected function getViewPath()
    {
        $key = $this->form->getResourceId();

        return $this->getListPath() . '/' . $key;
    }

    /**
     * Render list button.
     *
     * @return string
     */
    protected function renderList()
    {
        return view('admin::actions.list', [
            'label' => true,
            'href'  => $this->getListPath()
        ])->render();
    }

    /**
     * Render list button.
     *
     * @return string
     */
    protected function renderView()
    {
        return view('admin::actions.view', [
            'label' => true,
            'href'  => $this->getViewPath()
        ])->render();
    }

    /**
     * Render `delete` tool.
     *
     * @return string
     */
    protected function renderDelete()
    {
        return view('admin::actions.delete', [
            'label'     => true,
            'urlDelete' => $this->getDeletePath(),
            'urlList'   => $this->getListPath(),
            'callback' => 'list'
        ])->render();
    }

    /**
     * Add a tool.
     *
     * @param string $tool
     *
     * @return $this
     *
     * @deprecated use append instead.
     */
    public function add($tool)
    {
        return $this->append($tool);
    }

    /**
     * Disable back button.
     *
     * @return $this
     *
     * @deprecated
     */
    public function disableBackButton()
    {
    }

    /**
     * Disable list button.
     *
     * @return $this
     *
     * @deprecated Use disableList instead.
     */
    public function disableListButton()
    {
        return $this->disableList();
    }

    /**
     * Render custom tools.
     *
     * @param Collection $tools
     * @return mixed
     */
    protected function renderCustomTools($tools)
    {
        if (empty($tools)) {
            return '';
        }

        return $tools->map(function ($tool) {
            if ($tool instanceof Renderable) {
                return $tool->render();
            }

            if ($tool instanceof Htmlable) {
                return $tool->toHtml();
            }

            return (string)$tool;
        })->implode(' ');
    }

    /**
     * Render tools.
     *
     * @return string
     */
    public function render()
    {
        $actions = [$this->renderCustomTools($this->prepends)];

        foreach ($this->tools as $tool) {
            $method = 'render' . ucfirst($tool);
            array_push($actions, $this->$method());
        }

        return view('admin::actions.container', [
            'actions' => $actions
        ])->render();
    }
}
