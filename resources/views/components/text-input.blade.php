@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-brand-200 focus:border-brand-900 focus:ring-brand-900 rounded-md shadow-sm']) }}>
