<?php

namespace Dnwjn\NovaButton;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class ButtonGroup extends Field
{
    /** @var string */
    public $component = 'nova-button-group-field';

    /**
     * Indicates if the element should be shown on the creation view.
     *
     * @var (callable(\Laravel\Nova\Http\Requests\NovaRequest):(bool))|bool
     */
    public $showOnCreation = true;

    /**
     * Indicates if the element should be shown on the update view.
     *
     * @var (callable(\Laravel\Nova\Http\Requests\NovaRequest, mixed):(bool))|bool
     */
    public $showOnUpdate = true;

    /**
     * The contents of the Button field.
     *
     * @var array|\Illuminate\Support\Collection
     */
    public $buttons;

    /**
     * Create a new Button field.
     *
     * @param  string  $name
     * @param  string|array<int, class-string<\Laravel\Nova\Fields\Field>|callable>|null  $attribute
     * @param  array<int, class-string<\Laravel\Nova\Fields\Field>|callable>  $buttons
     * @return void
     */
    public function __construct($name, $attribute = null, $buttons = [])
    {
        if (is_array($attribute)) {
            $buttons = $attribute;
            $attribute = null;
        }

        parent::__construct($name, $attribute);

        $this->buttons = $buttons;
    }

    /**
     * Resolve the field's value for display.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolveForDisplay($resource, $attribute = null)
    {
        $this->prepareButtons($resource, $attribute);
    }

    /**
     * Prepare the stack for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'buttons' => collect($this->buttons)->all(),
        ]);
    }

    /**
     * Prepare each button for serialization.
     *
     * @param  mixed  $resource
     * @param  string  $attribute
     * @return void
     */
    public function prepareButtons($resource, $attribute = null)
    {
        $this->ensureButtonsAreResolveable();

        $request = app(NovaRequest::class);

        $this->buttons = $this->buttons->filter(function ($field) use ($request, $resource) {
            return true;
            // Lets just NOP this for now also
            if ($request->isResourceIndexRequest()) {
                return $field->isShownOnIndex($request, $resource);
            }

            return $field->isShownOnDetail($request, $resource);
        })->values()->each->resolveForDisplay($resource, $attribute);
    }

    /**
     * Ensure that each button for the field is resolvable.
     *
     * @return void
     */
    protected function ensureButtonsAreResolveable()
    {
        $this->buttons = collect($this->buttons)->map(function ($button) {
            // I think we can just get away with nulling this

            // if (is_callable($button)) {
            //     return GroupedButton::make('Anonymous', $button);
            // }

            return $button;
        });
    }
}
