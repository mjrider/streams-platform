<?php namespace Anomaly\Streams\Platform\Ui\Table\Component\Header;

use Anomaly\Streams\Platform\Ui\Table\TableBuilder;

/**
 * Class HeaderNormalizer
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Platform\Ui\Table\Component\Header
 */
class HeaderNormalizer
{

    /**
     * Normalize header input.
     *
     * @param TableBuilder $builder
     */
    public function normalize(TableBuilder $builder)
    {
        $columns = $builder->getColumns();

        foreach ($columns as $key => &$column) {

            /**
             * If the key is non-numerical then
             * use it as the header and use the
             * column as the value.
             */
            if (!is_numeric($key) && !is_array($column)) {
                $column = [
                    'heading' => $key,
                    'value'   => $column,
                ];
            }

            /**
             * If the column is just a string then treat
             * it as the header AND the value.
             */
            if (is_string($column)) {
                $column = [
                    'heading' => $column,
                    'value'   => $column,
                ];
            }
        }

        $builder->setColumns($columns);
    }
}
