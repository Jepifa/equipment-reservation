<?php

namespace App\Rules;

use App\Models\Equipment;
use App\Models\Manip;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Class UniqueEquipmentForManip
 *
 * This class defines a custom validation rule for ensuring that equipment is unique for a manipulation within a specified time slot.
 * It checks if the provided equipment is already reserved for the given time slot, considering recurrence if applicable.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class UniqueEquipmentForManip implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];
 
    protected $beginDate;
    protected $endDate;

    public function __construct(Carbon $beginDate = null, Carbon $endDate = null)
    {
        $this->beginDate = $beginDate;
        $this->endDate = $endDate;
    }
 
    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;
 
        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $isRecurrence = $this->beginDate !== null && $this->endDate !== null;
        if ($this->beginDate == null || $this->endDate == null) {
            $this->beginDate = Carbon::parse($this->data['beginDate']);
            $this->endDate = Carbon::parse($this->data['endDate']);
        }
        
        $currentManipId = $this->data['id'] ?? 0;

        $manips = Manip::where(function ($query) {
            $query->whereBetween('begin_date', [$this->beginDate, $this->endDate])
                ->orWhereBetween('end_date', [$this->beginDate, $this->endDate])
                ->orWhere(function ($query) {
                    $query->where('begin_date', '<', $this->beginDate)
                        ->where('end_date', '>', $this->endDate);
                });
        })->where('id', '!=', $currentManipId)->get();

        foreach ($manips as $manip) {
            foreach ($manip->equipment as $equipment) {
                if (in_array($equipment->id, $value)) {
                    $equipmentName = Equipment::find($equipment->id)->name;
                    $errorMessage = "The {$equipmentName} is already reserved for this time slot ({$this->beginDate} - {$this->endDate}).";
                    
                    if ($isRecurrence) {
                        $errorMessage .= " All recurrences after this time slot have not been added.";
                    }
                    $fail($errorMessage);
                }
            }
        }
    }
}
