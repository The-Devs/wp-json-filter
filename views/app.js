/*
    Plugins can be added via Vue components here.
    To add a <filter-el> component the JS must be defined here.
    HTML code can be built at `template` property of Vue component or via MJSONV\View::createElement( "filter-el", [ "attr" => "value|js expression" ], "valid html string as child of element" )
*/

const App = {
    el: "#mjsonv",
    data: {
        items: [],
        loading: false,
        waiting: false,
        idAttr: "",
        url: "",
        single: {}
    },
    methods: {
        getData: function () {
            this.loading = true;
            axios.get( this.url )
            .then( res => {
                this.items = res.data;
            } )
            .catch( err => {
                console.error( err );
            } )
            .finally( () => {
                this.loading = false;
            } )
        },
        getSingle: function ( id ) {
            this.waiting = true;
            axios.get( this.url + "/" + id )
            .then( res => {
                this.single = res.data;
            } )
            .catch( err => {
                console.error( err );
            } )
            .finally( () => {
                this.waiting = false;
            } )
        },
        toSingle: function ( ev ) {
            ev.preventDefault();
            let tr = ev.target;
            while ( tr.nodeName !== "TR" ) {
                tr = tr.parentNode;
            }
            const id = tr.querySelector( "[name=" + this.idAttr + "]" ).innerText;
            this.getSingle( id );
        },
    },
    mounted: function () {
        const app = document.querySelector( "#mjsonv" );
        const url = app.getAttribute( "mjsonv-url" );
        const idAttr = app.getAttribute( "mjsonv-id" );
        this.idAttr = idAttr;
        this.url = url;
        this.getData();
    }
}

new Vue( App );
