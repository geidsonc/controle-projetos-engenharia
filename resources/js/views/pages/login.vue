<template>
	<div
		class="d-flex justify-content-center align-items-center card-container"
	>
		<b-card
			title="Login"
			class="mb-2 card-style"
		>
			<div class="mt-4">CPF:</div>
			<b-form-input class="mt-1" v-model="cpf" placeholder="Informe seu CPF"></b-form-input>

			<div class="mt-3">Senha:</div>
			<b-form-input class="mt-1" type="password" v-model="password" placeholder="Informe sua senha"></b-form-input>
			<small v-if="!isAuthenticated && cpf.length > 0 && password.length > 0" class="text-danger">Usuário ou senha inválidos</small>

			<b-button
				class="mt-4"
				block
				variant="primary"
				@click="login"
			>
				Entrar
			</b-button>
		</b-card>
	</div>
</template>

<script>
  export default {
	data() {
	  return {
		cpf: '',
		password: '',
		isAuthenticated: true,
	  }
	},

	methods: {
		login() {
			this.axios
				.get('/auth/login', {params: {email: this.cpf, password: this.password}})
				.then(({data}) => {
					if (!!data) {
                        location.href = '/';
						// this.$router.push({name:'projects'});
						this.isAuthenticated = true;
					}
				}).catch((error) => {
					this.isAuthenticated = false;
				});
		}

	},
  }
</script>
<style>
	.card-container {
		height: 100vh;
	}

	.card-style {
		max-width: 20rem;
	}
</style>
