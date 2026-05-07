<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class TableOrderSyncRequest extends FormRequest
{
    private const ACTION_TYPES = [
        'add_menu_item',
        'add_custom_item',
        'set_item_quantity',
        'set_item_note',
        'remove_item',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_id' => ['required', 'string', 'max:100'],
            'actions' => ['required', 'array', 'min:1', 'max:100'],
            'actions.*.id' => ['required', 'string', 'max:80', 'distinct'],
            'actions.*.type' => ['required', 'string', 'in:' . implode(',', self::ACTION_TYPES)],
            'actions.*.payload' => ['required', 'array'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $actions = $this->input('actions', []);

            if (! is_array($actions)) {
                return;
            }

            foreach ($actions as $index => $action) {
                if (! is_array($action)) {
                    continue;
                }

                $type = $action['type'] ?? null;
                $payload = $action['payload'] ?? null;

                if (! is_string($type) || ! is_array($payload)) {
                    continue;
                }

                $errors = $this->validateActionPayload($type, $payload);

                foreach ($errors as $field => $message) {
                    $validator->errors()->add("actions.{$index}.payload.{$field}", $message);
                }
            }
        });
    }

    private function validateActionPayload(string $type, array $payload): array
    {
        return match ($type) {
            'add_menu_item' => $this->validateAddMenuPayload($payload),
            'add_custom_item' => $this->validateAddCustomPayload($payload),
            'set_item_quantity' => $this->validateSetQuantityPayload($payload),
            'set_item_note' => $this->validateSetNotePayload($payload),
            'remove_item' => $this->validateRemovePayload($payload),
            default => ['type' => 'Unsupported action type.'],
        };
    }

    private function validateAddMenuPayload(array $payload): array
    {
        $errors = [];

        if (! isset($payload['menu_item_id']) || ! is_numeric($payload['menu_item_id'])) {
            $errors['menu_item_id'] = 'menu_item_id is required.';
        }

        if (isset($payload['quantity']) && (! is_numeric($payload['quantity']) || (int) $payload['quantity'] < 1 || (int) $payload['quantity'] > 20)) {
            $errors['quantity'] = 'quantity must be between 1 and 20.';
        }

        if (isset($payload['local_ref']) && (! is_string($payload['local_ref']) || trim($payload['local_ref']) === '' || mb_strlen($payload['local_ref']) > 120)) {
            $errors['local_ref'] = 'local_ref must be a non-empty string.';
        }

        if (isset($payload['note']) && ! is_string($payload['note']) && $payload['note'] !== null) {
            $errors['note'] = 'note must be a string or null.';
        }

        return $errors;
    }

    private function validateAddCustomPayload(array $payload): array
    {
        $errors = [];

        if (! isset($payload['name']) || ! is_string($payload['name']) || trim($payload['name']) === '' || mb_strlen(trim($payload['name'])) > 100) {
            $errors['name'] = 'name is required and must be at most 100 characters.';
        }

        if (! isset($payload['unit_price']) || ! is_numeric($payload['unit_price']) || (float) $payload['unit_price'] < 0) {
            $errors['unit_price'] = 'unit_price is required and must be 0 or greater.';
        }

        if (! isset($payload['quantity']) || ! is_numeric($payload['quantity']) || (int) $payload['quantity'] < 1 || (int) $payload['quantity'] > 99) {
            $errors['quantity'] = 'quantity is required and must be between 1 and 99.';
        }

        if (isset($payload['local_ref']) && (! is_string($payload['local_ref']) || trim($payload['local_ref']) === '' || mb_strlen($payload['local_ref']) > 120)) {
            $errors['local_ref'] = 'local_ref must be a non-empty string.';
        }

        if (isset($payload['note']) && ! is_string($payload['note']) && $payload['note'] !== null) {
            $errors['note'] = 'note must be a string or null.';
        }

        return $errors;
    }

    private function validateSetQuantityPayload(array $payload): array
    {
        $errors = [];

        if (! array_key_exists('item_ref', $payload) || ! $this->isValidItemRef($payload['item_ref'])) {
            $errors['item_ref'] = 'item_ref is required.';
        }

        if (! isset($payload['quantity']) || ! is_numeric($payload['quantity']) || (int) $payload['quantity'] < 1 || (int) $payload['quantity'] > 99) {
            $errors['quantity'] = 'quantity is required and must be between 1 and 99.';
        }

        return $errors;
    }

    private function validateSetNotePayload(array $payload): array
    {
        $errors = [];

        if (! array_key_exists('item_ref', $payload) || ! $this->isValidItemRef($payload['item_ref'])) {
            $errors['item_ref'] = 'item_ref is required.';
        }

        if (array_key_exists('note', $payload) && ! is_string($payload['note']) && $payload['note'] !== null) {
            $errors['note'] = 'note must be a string or null.';
        }

        return $errors;
    }

    private function validateRemovePayload(array $payload): array
    {
        if (! array_key_exists('item_ref', $payload) || ! $this->isValidItemRef($payload['item_ref'])) {
            return ['item_ref' => 'item_ref is required.'];
        }

        return [];
    }

    private function isValidItemRef(mixed $value): bool
    {
        if (is_numeric($value)) {
            return (int) $value > 0;
        }

        if (! is_string($value)) {
            return false;
        }

        return trim($value) !== '' && mb_strlen($value) <= 120;
    }
}

