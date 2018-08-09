<?php

namespace Encore\Admin\Show;

use Encore\Admin\Admin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class Tools implements Renderable
{
    /**
     * The panel that holds this tool.
     *
     * @var Panel
     */
    protected $panel;

    /**
     * @var string
     */
    protected $resource;

    /**
     * Default tools.
     *
     * @var array
     */
    protected $tools = ['delete', 'edit', 'list'];

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
     * Tools constructor.
     *
     * @param Panel $panel
     */
    public function __construct(Panel $panel)
    {
        if (!config('admin.actions.list')) {
            $this->disableList();
        }
        if (!config('admin.actions.edit')) {
            $this->disableEdit();
        }
        if (!config('admin.actions.delete')) {
            $this->disableDelete();
        }

        $this->panel = $panel;

        $this->appends = new Collection();
        $this->prepends = new Collection();
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
     * Get resource path.
     *
     * @return string
     */
    public function getResource()
    {
        if (is_null($this->resource)) {
            $this->resource = $this->panel->getParent()->getResourcePath();
        }

        return $this->resource;
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
    public function disableEdit()
    {
        array_delete($this->tools, 'edit');

        return $this;
    }

    /**
     * Get request path for resource list.
     *
     * @return string
     */
    protected function getListPath()
    {
        return '/' . ltrim($this->getResource(), '/');
    }

    /**
     * Get request path for edit.
     *
     * @return string
     */
    protected function getEditPath()
    {
        $key = $this->panel->getParent()->getModel()->getKey();

        return $this->getListPath() . '/' . $key . '/edit';
    }

    /**
     * Get request path for delete.
     *
     * @return string
     */
    protected function getDeletePath()
    {
        $key = $this->panel->getParent()->getModel()->getKey();

        return $this->getListPath() . '/' . $key;
    }

    /**
     * Render `list` tool.
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
     * Render `edit` tool.
     *
     * @return string
     */
    protected function renderEdit()
    {
        return view('admin::actions.edit', [
            'label' => true,
            'href'  => $this->getEditPath()
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
            'callback'  => 'list'
        ])->render();
    }

    /**
     * Render custom tools.
     *
     * @param Collection $tools
     * @return mixed
     */
    protected function renderCustomTools($tools)
    {
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
