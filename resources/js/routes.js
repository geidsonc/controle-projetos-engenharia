import Login from './views/pages/login.vue';
import Projects from './views/pages/Projects.vue';

export default [
    {
		path: '/',
		redirect: '/login',
	},
	{
		path: '/login',
		component: Login,
    },
    {
        path: '/projects',
        name: 'projects',
		component: Projects,
	},
];