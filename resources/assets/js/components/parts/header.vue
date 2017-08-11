<template>
    <div class="row">
        <div class="col-sm-6">
            <div class="page-title">
                {{ name }}
            </div>
<!--             <div class="page-sub-header">
                Welcome Back
            </div> -->
        </div>
    </div>
</template>

<script>
export default {
    name: 'dx-header',
    data () {
        return {
        };
    },
    props: {
        name: {
            type: String,
            default: ''
        }
    }
}
</script>
