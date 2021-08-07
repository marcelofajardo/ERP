<div class="search-results" v-cloak v-show="searching">
    <div class="grid half v-center">
        <h3 class="text-muted px-none">
            {{ trans('bookstack::entities.search_results') }}
        </h3>
        <div class="text-right">
            <a v-if="searching" v-on:click="clearSearch" class="button outline">{{ trans('bookstack::entities.search_clear') }}</a>
        </div>
    </div>

    <div v-if="!searchResults">
        @include('bookstack::partials.loading-icon')
    </div>
    <div class="book-contents" v-html="searchResults"></div>
</div>