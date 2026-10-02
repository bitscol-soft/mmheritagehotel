@extends('layouts.master')
@section('title', 'UI component library')
@section('content')
<x-mm.styles />
<x-mm.page title="Component library" description="Laravel + Blade + Tailwind · isolated renovation layer">
    <x-slot name="actions"><a href="#fields" class="mm-button">Explore components</a></x-slot>
    <x-mm.panel>
        <h2 class="tw-m-0 tw-mb-3 tw-text-xl tw-font-semibold">A calmer workspace</h2>
        <p class="tw-text-muted">Shared components for the next generation of hotel screens. Legacy Bootstrap and JavaScript remain in place.</p>
        <x-mm.badge>Foundation / Wave 1</x-mm.badge>
    </x-mm.panel>
    <x-mm.panel id="fields">
        <h2 class="tw-m-0 tw-mb-4 tw-text-xl tw-font-semibold">Fields and actions</h2>
        <div class="tw-grid tw-gap-4 sm:tw-grid-cols-2">
            <x-mm.field label="Guest name" id="demo-name" name="demo_name" placeholder="Enter a name" />
            <x-mm.field label="Email" id="demo-email" name="demo_email" type="email" value="invalid" error="Enter a valid email address." />
        </div>
        <div class="tw-flex tw-flex-wrap tw-gap-2 tw-mt-4">
            <button type="button" class="mm-button">Primary action</button>
            <button type="button" class="mm-button mm-button-secondary">Secondary action</button>
        </div>
    </x-mm.panel>
    <div class="mm-panel tw-overflow-hidden">
        <x-mm.table-scroll label="Example records — fictional data">
            <table>
                <caption class="tw-p-4 tw-text-muted">Example records · fictional data</caption>
                <thead><tr><th scope="col">Guest</th><th scope="col">Reference</th><th scope="col">Status</th></tr></thead>
                <tbody><tr><td>Sample guest</td><td>DEMO-001</td><td><x-mm.badge>Example</x-mm.badge></td></tr></tbody>
            </table>
        </x-mm.table-scroll>
    </div>
</x-mm.page>
@endsection
