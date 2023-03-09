<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    public const ENTITY_TYPE_USER = 1;
    public const ENTITY_TYPE_COMPANY = 2;
    public const ENTITY_TYPE_SITE = 3;

    protected $fillable = [
        'entity_type',
        'entyty_id',
        'label',
    ];

    static public function addLabels(
        int $entity_type,
        int $entity_id,
        array $labels
    ):void
    {
        if (empty($labels)) {
            throw new \Exception('Список лейблов пустой!');
        }

        foreach ($labels as $label) {
            $labelObject = new \App\Models\Label();
            $labelObject->entity_type = $entity_type;
            $labelObject->entity_id = $entity_id;
            $labelObject->label = $label;
            $labelObject->save();
        }
    }

    static public function deleteLabels(
        int $entity_type,
        int $entity_id,
        array $labels
    ):void
    {
        if (empty($labels)) {
            throw new \Exception('Список лейблов пустой!');
        }

        foreach ($labels as $label) {
            $labelObject = \App\Models\Label::where('entity_type', '=', $entity_type)
                                            ->where('entity_id', '=', $entity_id)
                                            ->where('label', '=', $label)
                                            ->get();
            if (count($labelObject) == 0) {
                throw new \Exception('У сущности нету лейблов: ' . implode(', ', $labels));
            } else {
                $labelObject[0]->delete();
            }
        }
    }

    static public function updateLabels(
        int $entity_type,
        int $entity_id,
        array $labels
    ):void
    {
        $entityLabelObjects = \App\Models\Label::where('entity_type', '=', $entity_type)
                                            ->where('entity_id', '=', $entity_id)
                                            ->get();
        foreach ($entityLabelObjects as $entityLabelObject) {
            $entityLabelObject->delete();
        }

        if (!empty($labels)) {
            \App\Models\Label::addLabels($entity_type, $entity_id, $labels);
        }

        $entityLabels = Label::getLabels($entity_type, $entity_id);
        $diff_labels = array_diff($labels, $entityLabels);
        if (!empty($diff_labels)) {
            throw new \Exception('Лейблы ' . implode(', ', $diff_labels) . ' не записались в бд');
        }
    }

    static public function getLabels(
        int $entity_type,
        int $entity_id,
    ): array
    {
        $labels = [];
        $labelObjects = \App\Models\Label::where('entity_type', '=', $entity_type)
                                        ->where('entity_id', '=', $entity_id)
                                        ->get();
        foreach ($labelObjects as $labelObject) {
            $labels[] = $labelObject->label;
        }
        return $labels;
    }
}
