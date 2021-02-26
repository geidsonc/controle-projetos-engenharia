<template>
	<b-container
        fluid
		class="py-3 px-5"
	>
        <b-row>
            <b-col cols="11">
                <h2>Lista de projetos</h2>
                <h6>Emita pareceres e gerencie projetos acompanhados por você</h6>
            </b-col>
        </b-row>

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
            style="font-size: 15px !important"
		>
            <template #cell(name)="row">
				{{row.item.name}}
			</template>

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
				<b-button size="sm" class="mr-1" variant="outline-primary" @click="showModalParecer(row.item.id)">
					Emitir parecer
				</b-button>
			</template>

			 <template #cell(more_actions)="row">
				<b-dropdown size="md"  variant="link" toggle-class="text-decoration-none" no-caret>
					<template #button-content>
						<b-icon-three-dots-vertical variant="secondary"></b-icon-three-dots-vertical>
					</template>

					<b-dropdown-item>Editar</b-dropdown-item>
					<b-dropdown-item @click="showDeleteDialog(row.item)">Excluir</b-dropdown-item>
				</b-dropdown>
			</template>

		</b-table>

		<b-modal
            ref="modal-parecer"
            title="Emitir parecer"
            okTitle="Emitir parecer"
            cancel-title="Fechar"
            @ok="criarParecer"
        >
			<div class="mt-3">Situação:</div>
            <b-form-select v-model="parecer.status" :options="optionsStatusParecer"></b-form-select>

			<div class="mt-3">Observação:</div>
			<b-form-textarea
				id="textarea"
				v-model="parecer.technical_opinion "
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
                        key: 'name',
						label: 'Título',
						sortable: true
                    },
					{
						key: 'user_name',
						label: 'Técnico',
						sortable: true
					},
					{
						key: 'start_date',
						label: 'Início',
						sortable: true
					},
					{
						key: 'end_date',
						label: 'Fim',
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
				],
                optionsStatusParecer: [
                    { value: '1', text: 'Em análise' },
                    { value: '2', text: 'Em diligência' },
                    { value: '3', text: 'Em execução' },
                    { value: '4', text: 'Concluído com pendência' },
                    { value: '5', text: 'Concluído sem pendência' },
                    { value: '6', text: 'Cancelado' },
                ],
				parecer: {
					project_id: null,
					status: 1,
					technical_opinion: '',
				}
			}
		},

		mounted() {
			this.listProjects();
		},

		methods: {
            listProjects() {
                this.axios
				.get('/project')
				.then(({data}) => {
                    this.items = data;
				});
            },
            showModalParecer(id) {
                this.parecer.project_id = id;
                this.$refs['modal-parecer'].show();
			},

			showRegisterModal() {
				this.$refs['register-modal'].show();
			},

			onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length
        this.currentPage = 1
			},

			showDeleteDialog(project) {
				this.$bvModal.msgBoxOk(`Realmente deseja excluir o convênio ${project.agreement_number}? A ação não poderá ser desfeita.`, {
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
						 this.axios.delete(`/project/${project.id}`)
                            .then(({data}) => {
                                this.$bvToast.toast(`Convênio ${project.agreement_number} deletado`, {
                                    title: 'Deletado',
                                    autoHideDelay: 5000,
                                    appendToast: append
                                })
                                this.listProjects();
                            });
					})
					.catch(err => {
						this.$bvModal.msgBoxOk(`Sem permissão para deletar`, {
                            title: 'Aviso',
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
                        });
			        });
            },

            criarParecer() {
                console.log(this.parecer);

                this.axios
                    .post(`/project/${this.parecer.project_id}/status`, {
                        'status': this.parecer.status,
                        'technical_opinion': this.parecer.technical_opinion,
                    })
                    .then(({data}) => {
                        this.$refs['modal-parecer'].hide();
                        this.listProjects();
                    });
            }
    }
	}
</script>
