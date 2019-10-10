<template>
    <div class="container" v-if="$gate.isAdmin()">
        <div id="#app" class="row justify-content-center">

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Developer </div>

                    <div class="card-body">
                        Yor are Developer Mood.
                    </div>
                </div>
            </div>
            </div>

    </div>
</template>

<script>
    export default {

    }
</script>
