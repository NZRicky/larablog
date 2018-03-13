
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});


/**
 * Common event:
 *
 * [data-link=....]  click event & redirect to specified link
 * [data-link-destroy=...] click event & ajax post(delete) to destory record
 */

// click event & redirect to specified link
$("[data-link]").click(function(){
   window.location.href = $(this).attr('data-link');
});

/**
 * click event & ajax post(delete) to destory record
 *
 * Ajax Request
 *
 * Done: Redirect to the link if specify redirect property
 */
$("[data-link-destroy]").click(function(){
    if (confirm("Are you sure?")) {
        $.ajax({
            url:$(this).attr("data-link-destroy"),
            method: "delete",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(data){
            if (typeof data.redirect != 'undefined') {
                window.location.href = data.redirect;
            }
        }).fail(function(){

        });
    }

});