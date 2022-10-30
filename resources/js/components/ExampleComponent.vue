<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an example component.
                      <button @click="increment">counter</button>
                      <span>{{ number }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  name: "ExampleComponent",
  data() {
    return {
      number: 0,
    }
  },
  methods: {
    increment() {
      this.number += 1;
    }
  }
}
</script>
