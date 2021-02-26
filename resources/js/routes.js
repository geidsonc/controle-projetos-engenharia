import Projects from './views/pages/projects.vue';

export default [
    {
		path: '/',
		redirect: '/projects',
	},
    {
        path: '/projects',
        name: 'projects',
		component: Projects,
	},
];
