<template>
	<b-card
    title="Login"
    style="max-width: 20rem;"
    class="mb-2"
  >
	<div class="mt-2">CPF:</div>
	<b-form-input v-model="cpf" placeholder="Informe seu CPF"></b-form-input>

	<div class="mt-2">Senha:</div>
	<b-form-input type="password" v-model="password" placeholder="Informe sua senha"></b-form-input>

    <b-button
		class="mt-4"
		block
		variant="primary"
		@click="login"
	>
		Login
	</b-button>
  </b-card>
</template>

<script>
  export default {
	data() {
	  return {
		cpf: '',
		password: '',
	  }
	},

	methods: {
		login() {
			let t = this.axios.get('/auth/login', {params: {email: this.cpf, password: this.password}}).then(({data}) => {
				
				if (data === 1) {
					this.$router.push({name:'projects'});
				}
			});
		}

	},
  }
</script>