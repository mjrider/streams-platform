<?php namespace Anomaly\Streams\Platform\Assignment\Form;

use Anomaly\Streams\Platform\Field\Contract\FieldRepositoryInterface;

/**
 * Class AssignmentFormFields
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Platform\Assignment\Form
 */
class AssignmentFormFields
{

    /**
     * Handle the form fields.
     *
     * @param AssignmentFormBuilder $builder
     */
    public function handle(AssignmentFormBuilder $builder)
    {
        $builder->setFields(
            [
                'stream_id'    => [
                    'hidden'   => true,
                    'readonly' => true,
                    'required' => true,
                    'disabled' => 'edit',
                    'value'    => $builder->getStreamId(),
                    'type'     => 'anomaly.field_type.text'
                ],
                'field_id'     => [
                    'label'        => 'streams::assignment.field.label',
                    'instructions' => 'streams::assignment.field.instructions',
                    'type'         => 'anomaly.field_type.select',
                    'disabled'     => 'edit',
                    'required'     => true,
                    'value'        => $builder->getFieldId(),
                    'config'       => [
                        'options' => function (FieldRepositoryInterface $fields) use ($builder) {

                            $fields = $fields->findByNamespace($builder->getStream()->getNamespace());

                            if ($builder->getFormMode() === 'create') {
                                $fields = $fields->unassigned();
                            }

                            return $fields->lists('name', 'id');
                        }
                    ]
                ],
                'required'     => [
                    'label'        => 'streams::assignment.required.label',
                    'instructions' => 'streams::assignment.required.instructions',
                    'type'         => 'anomaly.field_type.boolean',
                    'disabled'     => 'edit'
                ],
                'unique'       => [
                    'label'        => 'streams::assignment.unique.label',
                    'instructions' => 'streams::assignment.unique.instructions',
                    'type'         => 'anomaly.field_type.boolean',
                    'disabled'     => 'edit'
                ],
                'translatable' => [
                    'label'        => 'streams::assignment.translatable.label',
                    'instructions' => 'streams::assignment.translatable.instructions',
                    'type'         => 'anomaly.field_type.boolean',
                    'disabled'     => 'edit'
                ],
                'label'        => [
                    'label'        => 'streams::assignment.label.name',
                    'instructions' => 'streams::assignment.label.instructions',
                    'type'         => 'anomaly.field_type.text'
                ],
                'instructions' => [
                    'label'        => 'streams::assignment.instructions.name',
                    'instructions' => 'streams::assignment.instructions.instructions',
                    'type'         => 'anomaly.field_type.textarea'
                ]
            ]
        );
    }
}