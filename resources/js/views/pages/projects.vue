<template>
	<b-container
		class="mt-5"
	>
		<h2>Lista de projetos</h2>
		<h6>Emita pareceres e gerencie projetos acompanhados por você</h6>

		<div class="d-flex justify-content-between mt-5 align-items-center">
			<b-form-group
				label-for="filter-input"
				label-size="sm"
				class="col-8 p-0"
			>
				<b-input-group size="sm">
					<b-form-input
						id="filter-input"
						v-model="filter"
						type="search"
						placeholder="Filtre a lista com informações sobre os projetos"
					></b-form-input>

					<b-input-group-append>
						<b-button :disabled="!filter" @click="filter = ''">Limpar</b-button>
					</b-input-group-append>
				</b-input-group>
			</b-form-group>

			<b-button class="align-self-start" size="sm" variant="outline-primary" @click="showRegisterModal">
				Cadastrar projeto
			</b-button>
		</div>

		<b-table
			class="mt-4"
			hover
			:items="items"
			:fields="fields"
			:filter="filter"
      :filter-included-fields="filterOn"
			@filtered="onFiltered"
		>

            <template #cell(user_name)="row">
                <div
                    v-for="user in row.item.users"
                    :key="user.id"
                >
				    {{user.name}}
                </div>
			</template>

            <template #cell(action)="row">
				{{row.item.action}} - {{ row.item.action_value }}
			</template>

			<template #cell(status)="row">
				<b-badge pill variant="info">{{row.item.project_status[0].status_value}}</b-badge>
			</template>

			 <template #cell(button)="row">
				<b-button size="sm" class="mr-1" variant="outline-primary" @click="showModal">
					Emitir parecer
				</b-button>
			</template>

			 <template #cell(more_actions)="row">
				<b-dropdown size="md"  variant="link" toggle-class="text-decoration-none" no-caret>
					<template #button-content>
						<b-icon-three-dots-vertical variant="secondary"></b-icon-three-dots-vertical>
					</template>

					<b-dropdown-item>Editar</b-dropdown-item>
					<b-dropdown-item @click="showDeleteDialog">Excluir</b-dropdown-item>
				</b-dropdown>
			</template>

		</b-table>

		<b-modal ref="my-modal" title="Emitir parecer" okTitle="Emitir parecer" cancel-title="Fechar">
			<div class="mt-3">Situação:</div>
			<b-form-input class="mt-1" v-model="cpf" placeholder="Informe a situação do projeto"></b-form-input>

			<div class="mt-3">Observação:</div>
			<b-form-textarea
				id="textarea"
				v-model="text"
				placeholder="Informe alguma observação, caso necessário"
				rows="3"
				max-rows="6"
				class="mb-3"
			></b-form-textarea>
		</b-modal>

		<b-modal ref="register-modal" title="Cadastrar projeto" okTitle="Cadastrar" cancel-title="Fechar">
			<div class="mt-3">Nº de convênio:</div>
			<b-form-input class="mt-1" v-model="cpf" placeholder="Informe a situação do projeto"></b-form-input>

			<div class="mt-3">Nº Conv. P+ BR:</div>
			<b-form-input class="mt-1" v-model="cpf" placeholder="Informe a situação do projeto"></b-form-input>

			<div class="mt-3">Nº do processo de Projeto:</div>
			<b-form-input class="mt-1" v-model="cpf" placeholder="Informe a situação do projeto"></b-form-input>

			<div class="mt-3">Nº do processo de Convênio</div>
			<b-form-input class="mt-1" v-model="cpf" placeholder="Informe a situação do projeto"></b-form-input>

		</b-modal>

	</b-container>
</template>

<script>
	export default {
		data() {
			return {
				filter: null,
        filterOn: [],
				fields: [
					{
						key: 'process_number',
						label: 'Nº do processo',
						sortable: true
					},
                    {
						key: 'agreement_number',
						label: 'Nº de convênio',
						sortable: true
					},
					{
						key: 'user_name',
						label: 'Técnico',
						sortable: true
					},
					{
						key: 'city',
						label: 'Cidade',
						sortable: true
					},
					{
						key: 'action',
						label: 'Ação',
					},
					{
						key: 'status',
						label: 'Situação',
						sortable: true
					},
					{ key: 'button', label: '' },
					{ key: 'more_actions', label: '' }
				],
				items: [
				]
			}
		},

		mounted() {
			this.axios
				.get('/project')
				.then(({data}) => {
                    this.items = data;
				});
		},

		methods: {
      showModal() {
        this.$refs['my-modal'].show();
			},

			showRegisterModal() {
				this.$refs['register-modal'].show();
			},

			onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length
        this.currentPage = 1
			},

			showDeleteDialog() {
				this.$bvModal.msgBoxOk('Realmente deseja excluir o convênio 9999/AAAA? A ação não poderá ser desfeita.', {
					title: 'Confirmação de exclusão',
					size: 'md',
					buttonSize: 'md',
					okVariant: 'primary',
					cancelVariant: 'danger',
					headerClass: 'p-4 border-bottom-0',
					centered: true,
					okTitle: 'Sim',
					cancelTitle: 'Não',
          footerClass: 'p-2',
          hideHeaderClose: false,
				})
					.then(value => {
						//
					})
					.catch(err => {
						// An error occurred
					})
			},
    }
	}
</script>
