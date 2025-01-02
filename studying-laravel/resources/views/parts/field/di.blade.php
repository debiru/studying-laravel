<?php
$key = $args['key'];
$label = $args['label'];
$type = $args['type'] ?? 'text';
$enum = $args['enum'] ?? null;
$description = $args['description'] ?? null;
$placeholder = $args['placeholder'] ?? null;
$required = $args['required'] ?? false;
$badge = $args['badge'] ?? true;
$pattern = $args['pattern'] ?? null;
$dataPatternRef = $args['data-pattern-ref'] ?? null;
$dataPatternRefLabel = $args['data-pattern-ref-label'] ?? null;
if ($enum) $enum = call_user_func(implode('\\', array_merge(['App', 'Enums'], explode('/', $enum))) . '::arrayWithLabel');
$fieldId = fn($key) => $key === null ? null : sprintf('field-%s', $key);
?>
<div class="di mod-FieldDi">
  <dt class="labelBlock">
    <label for="{{ $fieldId($key) }}">{{ $label }}</label>
@if ($badge && $required)
    <span class="badge required">必須</span>
@endif
  </dt>
@if ($description)
  <dd class="descriptionBlock">
    <p>※{{ $description }}</p>
  </dd>
@endif
@if ($errors->has($key))
  <dd class="errorBlock">
    <ul class="errorMessages">
@foreach ($errors->get($key) as $message)
      <li>{{ $message }}</li>
@endforeach
    </ul>
  </dd>
@else
  <dd class="errorBlock"></dd>
@endif
  <dd class="fieldBlock">
@if ($type === 'radio')
@if ($enum)
    <ul class="items">
@foreach ($enum as $enumKey => $enumLabel)
<?php
$attrs = [
  'type' => 'radio',
  'name' => $key,
  'value' => $enumKey,
  'checked' => X::if_val(old($key) === $enumKey, true),
];
?>
      <li><label><span class="label">{{ $enumLabel }}</span> <input<?php X::attrs($attrs) ?>></label></li>
@endforeach
    </ul>
@endif
@else
<?php
$attrs = [
  'id' => $fieldId($key),
  'type' => $type,
  'name' => $key,
  'value' => old($key),
  'placeholder' => X::if_val($placeholder),
  'required' => X::if_val($required, true),
  'pattern' => X::if_val($pattern),
  'data-pattern-ref' => X::if_val($fieldId($dataPatternRef)),
  'data-pattern-ref-label' => X::if_val($dataPatternRefLabel),
];
?>
    <p><input<?php X::attrs($attrs) ?>></p>
@endif
  </dd>
</div>
