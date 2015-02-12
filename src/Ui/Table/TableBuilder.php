<?php namespace Anomaly\Streams\Platform\Ui\Table;

use Anomaly\Streams\Platform\Ui\Table\Command\BuildTable;
use Anomaly\Streams\Platform\Ui\Table\Command\LoadTable;
use Anomaly\Streams\Platform\Ui\Table\Contract\TableModelInterface;
use Anomaly\Streams\Platform\Ui\Table\Event\TableWasPosted;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TableBuilder
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Platform\Ui\Table
 */
class TableBuilder
{

    use DispatchesCommands;

    /**
     * The table model.
     *
     * @var null|string
     */
    protected $model = null;

    /**
     * The views configuration.
     *
     * @var array|string
     */
    protected $views = [];

    /**
     * The filters configuration.
     *
     * @var array|string
     */
    protected $filters = [];

    /**
     * The columns configuration.
     *
     * @var array|string
     */
    protected $columns = [];

    /**
     * The buttons configuration.
     *
     * @var array|string
     */
    protected $buttons = [];

    /**
     * The actions configuration.
     *
     * @var array|string
     */
    protected $actions = [];

    /**
     * The table object.
     *
     * @var Table
     */
    protected $table;

    /**
     * Create a new TableBuilder instance.
     *
     * @param Table $table
     */
    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    /**
     * Build the table.
     */
    public function build()
    {
        $this->dispatch(new BuildTable($this));

        if (app('request')->isMethod('post')) {
            app('events')->fire(new TableWasPosted($this->table));
        }
    }

    /**
     * Make the table response.
     */
    public function make()
    {
        $this->build();

        if ($this->table->getResponse() === null) {

            $this->dispatch(new LoadTable($this->table));

            $options = $this->table->getOptions();
            $data    = $this->table->getData();

            $this->table->setContent(
                view($options->get('table_view', 'streams::ui/table/index'), $data)
            );
        }
    }

    /**
     * Render the table.
     *
     * @return View|Response
     */
    public function render()
    {
        $this->make();

        if ($this->table->getResponse() === null) {

            $options = $this->table->getOptions();
            $content = $this->table->getContent();

            return view($options->get('wrapper_view', 'streams::blank'), compact('content'));
        }

        return $this->table->getResponse();
    }

    /**
     * Get the table object.
     *
     * @return Table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set the table model.
     *
     * @param string $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get the table model.
     *
     * @return null|string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set the views configuration.
     *
     * @param $views
     * @return $this
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get the views configuration.
     *
     * @return array
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set the filters configuration.
     *
     * @param $filters
     * @return $this
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Get the filters configuration.
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Set the columns configuration.
     *
     * @param $columns
     * @return $this
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Get the columns configuration.
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Set the buttons configuration.
     *
     * @param $buttons
     * @return $this
     */
    public function setButtons($buttons)
    {
        $this->buttons = $buttons;

        return $this;
    }

    /**
     * Get the buttons configuration.
     *
     * @return array
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * Set the actions configuration.
     *
     * @param $actions
     * @return $this
     */
    public function setActions($actions)
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * Get the actions configuration.
     *
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Get the table's stream.
     *
     * @return \Anomaly\Streams\Platform\Stream\Contract\StreamInterface|null
     */
    public function getTableStream()
    {
        return $this->table->getStream();
    }

    /**
     * Get a table option value.
     *
     * @param      $key
     * @param null $default
     * @return mixed
     */
    public function getTableOption($key, $default = null)
    {
        return $this->table->getOption($key, $default);
    }

    /**
     * Set a table option value.
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function setTableOption($key, $value)
    {
        $this->table->setOption($key, $value);

        return $this;
    }
}
