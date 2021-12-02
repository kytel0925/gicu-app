<template>
  <div>
      <v-btn icon @click="add">
          <v-icon>mdi-plus</v-icon>
      </v-btn>
      <span>value: {{ this.value }}</span>
      <span>content: {{ this.value }}</span>
      <v-btn icon @click="sub">
          <v-icon>mdi-minus</v-icon>
      </v-btn>
  </div>
</template>

<script>
export default {
    name: "TestModel",
    props: ['value'],
    data() {
        return {
            content: this.value,
        }
    },
    methods: {
        add() {
            this.$emit('input', this.content++)
        },

        sub() {
            this.$emit('input', this.content--)
        },
    }
}
</script>

<style scoped>

</style>
