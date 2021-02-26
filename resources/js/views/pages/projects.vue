<template>
	<b-container
				fluid
		class="py-3 px-5"
	>
				<b-row>
						<b-col cols="8">
								<h2>Lista de projetos</h2>
								<h6>Emita pareceres e gerencie projetos acompanhados por você</h6>
						</b-col>
                        <b-col class="text-right" v-if="user.name">
                            <span class="font-weight-bold">
                                Bem vindo, {{ user.name }}
                            </span> | <a href="/auth/logout">Sair</a>
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

			<b-button v-if="user.type == 'A'" class="align-self-start" size="sm" variant="outline-primary" @click="showRegisterModal">
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

					<b-dropdown-item @click="showEditProject(row.item.id)">Editar</b-dropdown-item>
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

		<b-modal
			scrollable
			ref="register-modal"
			title="Cadastrar projeto"
			okTitle="Cadastrar"
			cancel-title="Fechar"
            no-close-on-backdrop
            ok-title-html="Salvar"
			@ok="createOrEditProject"
		>
            <b-form>

                <div class="mt-3"><span class="text-danger">*</span>Título:</div>
                <b-form-input class="mt-1" v-model="project_data.name" placeholder="Informe o título do projeto"></b-form-input>

                <div class="mt-3"><span class="text-danger">*</span>Nº do processo:</div>
                <b-form-input class="mt-1" v-model="project_data.process_number" placeholder="Informe o número do processo do projeto"></b-form-input>

                <div class="mt-3"><span class="text-danger">*</span>Nº de convênio:</div>
                <b-form-input class="mt-1" v-model="project_data.agreement_number" placeholder="Informe o número de convênio do projeto"></b-form-input>

                <div class="mt-3"><span class="text-danger">*</span>Ação:</div>
                <b-form-select v-model="project_data.action" :options="actionsOptions"></b-form-select>

                <b-row>
                    <b-col>
                        <div class="mt-3"><span class="text-danger">*</span>Data de início</div>
                        <b-form-datepicker
                            v-model="project_data.start_date"
                            class="mt-1"
                            placeholder="Selecione uma data"
                            :date-format-options="{ year: 'numeric', month: '2-digit'}"
                            locale="pt-br"
                        >
                        </b-form-datepicker>
                    </b-col>
                    <b-col>
                        <div class="mt-3"><span class="text-danger">*</span>Data de fim</div>
                        <b-form-datepicker
                            v-model="project_data.end_date"
                            class="mt-1"
                            placeholder="Selecione uma data"
                            :date-format-options="{ year: 'numeric', month: '2-digit'}"
                            locale="pt-br"
                        >
                        </b-form-datepicker>
                    </b-col>
                </b-row>

                <div class="mt-3"><span class="text-danger">*</span>Situação:</div>
                <b-form-select v-model="project_data.status" :options="optionsStatusParecer"></b-form-select>

                <div class="mt-3"><span class="text-danger">*</span>Técnico responsável:</div>
                <b-form-select v-model="project_data.user" :options="users"></b-form-select>

                <div class="mt-3"><span class="text-danger">*</span>Localidade:</div>
                <b-form-input class="mt-1" v-model="project_data.city" placeholder="Informe a localidade"></b-form-input>

                <div class="mt-3">Resumo do projeto:</div>
                <b-form-textarea
                    id="textarea"
                    v-model="project_data.resume"
                    placeholder="Adicione informações relevantes sobre o projeto"
                    rows="3"
                    max-rows="6"
                    class="mb-4"
                />
            </b-form>
		</b-modal>

	</b-container>
</template>

<script>
	export default {
		data() {
			return {
                user: {
                    name: '',
                    type: null,
                },
				users: [],
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
				actionsOptions: [
						{ value: 'MSD', text: 'MSD - Melhorias sanitárias domiciliares' },
						{ value: 'MH', text: 'MH - Melhoria habitacional' },
						{ value: 'SAA', text: 'SAA - Sistema de abastecimento de água' },
						{ value: 'SES', text: 'SES - Sistema de esgotamento sanitário' },
						{ value: 'RES', text: 'RES - Resíduos' },
						{ value: 'DRE', text: 'DRE - Drenagem' },
				],
				project_data: {
                    id: null,
					name: '',
					process_number: '',
					agreement_number: '',
                    start_date: null,
                    end_date: null,
					city: '',
					status: null,
					action: null,
					user: null,
					resume: '',
				},
				parecer: {
					project_id: null,
					status: 1,
					technical_opinion: '',
				}
			}
		},

		mounted() {
			this.listProjects();

            this.getUserAuthenticated();
			this.getUsers();
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
                this.project_data = {
                    id: null,
					name: '',
					process_number: '',
					agreement_number: '',
                    start_date: null,
                    end_date: null,
					city: '',
					status: null,
					action: null,
					user: null,
					resume: '',
				};

				this.$refs['register-modal'].show();
			},

            showEditProject(id) {
                this.project_data.id = id;

                this.axios.get(`project/${id}`)
                    .then(({data}) => {
                        this.project_data.name = data.name;
                        this.project_data.process_number = data.process_number;
                        this.project_data.agreement_number = data.agreement_number;
                        this.project_data.start_date = data.start_date;
                        this.project_data.end_date = data.end_date;
                        this.project_data.city = data.city;
                        this.project_data.status = data.project_status.reverse()[0].status;
                        this.project_data.action = data.action;
                        this.project_data.user = data.users[0].id;
                        this.project_data.resume = (data.resume || '');
                    });

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
                                    this.showToast('Deletado', `Convênio ${project.agreement_number} deletado`);
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
                            footerClass: 'p-2',
                            hideHeaderClose: false,
						});
					});
			},

			criarParecer() {
					this.axios
						.post(`/project/${this.parecer.project_id}/status`, {
								'status': this.parecer.status,
								'technical_opinion': this.parecer.technical_opinion,
						})
						.then(({data}) => {
                                this.showToast('Sucesso', 'Parecer criado com sucesso');
								this.$refs['modal-parecer'].hide();
								this.listProjects();
						});
			},

			getUsers() {
				this.axios
				.get('/user')
				.then(({data}) => {
					this.users = data.map((user_data) => {
						const { id: value, name: text, id: id} = user_data;
						const filtered_data = {value, text, id};
						return filtered_data;
					});
				});
			},

			createOrEditProject(ev) {
                ev.preventDefault();

                if (!this.validaFieldsProject()) {
                    this.showMessageDialog('Aviso', 'Campos obrigatórios não preenchidos');
                    return;
                }

                if (this.project_data.id != null) {
                    this.editProject();
                } else {
                    this.createProject();
                }
			},

            createProject() {
                this.axios
                    .post('/project', {
                        ...this.project_data,
                        users_ids: [this.project_data.user],
                    })
                    .then((data) => {
                        this.showToast('Sucesso', 'Novo projeto cadastrado');
                        this.$bvToast.toast(`Novo projeto cadastrado`, {
                            title: 'Sucesso',
                            autoHideDelay: 5000,
                            appendToast: false
                        })
                        this.$refs['register-modal'].hide();
                        this.listProjects();
                    })
                    .catch(({response}) => {
						this.showMessageDialog('Aviso', response.data.message);
                    });
            },

            editProject() {
                this.axios
                    .put(`/project/${this.project_data.id}`, {
                        ...this.project_data,
                        users_ids: [this.project_data.user],
                    })
                    .then((data) => {
                        this.showToast('Sucesso', `Projeto convênio nº ${this.project_data.agreement_number} atualizado com sucesso`);
                        this.$refs['register-modal'].hide();
                        this.listProjects();
                    })
                    .catch(({response}) => {
						this.showMessageDialog('Aviso', response.data.message);
                    });
            },

            getUserAuthenticated() {
                this.axios
                    .get('/auth/user')
                    .then(({data}) => {
                        this.user = data;
                    });
            },

            validaFieldsProject() {
                return !(
                    this.project_data.name == '' ||
                    this.project_data.process_number == '' ||
                    this.project_data.agreement_number == '' ||
                    this.project_data.start_date == null ||
                    this.project_data.end_date == null ||
                    this.project_data.city == '' ||
                    this.project_data.status == null ||
                    this.project_data.action == null ||
                    this.project_data.user == null
                );
            },

            showMessageDialog(title, message) {
                this.$bvModal.msgBoxOk(message, {
								title: title,
								size: 'md',
								buttonSize: 'md',
								okVariant: 'primary',
								cancelVariant: 'danger',
								headerClass: 'border-bottom-0',
								centered: true,
								okTitle: 'Fechar',
								hideHeaderClose: false,
						});
            },

            showToast(title, message) {
                this.$bvToast.toast(message, {
                    title: title,
                    autoHideDelay: 5000,
                    appendToast: false,
                    toaster: 'b-toaster-bottom-left',
                    variant: 'success'
                });
            }
		}
	}
</script>
