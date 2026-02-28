<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       http://themeforest.net/user/G5Themes
 * @since      1.0.0
 *
 * @package    Essential_Real_Estate
 * @subpackage Essential_Real_Estate/includes
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Loader')) {
	/**
	 * Register all actions and filters for the plugin
	 * Class ERE_Loader
	 */
	class ERE_Loader
	{

		/**
		 * The array of actions registered with WordPress.
		 */
		protected $actions;

		/**
		 * The array of filters registered with WordPress.
		 */
		protected $filters;

		/**
		 * Initialize the collections used to maintain the actions and filters.
		 */
		public function __construct()
		{
			$this->actions = array();
			$this->filters = array();
		}
		/**
		 * Add a new action to the collection to be registered with WordPress
		 * @param $hook
		 * @param $component
		 * @param $callback
		 * @param int $priority
		 * @param int $accepted_args
		 */
		public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1)
		{
			$this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
		}
		/**
		 * Add a new filter to the collection to be registered with WordPress
		 * @param $hook
		 * @param $component
		 * @param $callback
		 * @param int $priority
		 * @param int $accepted_args
		 */
		public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1)
		{
			$this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
		}
		/**
		 * A utility function that is used to register the actions and hooks into a single collection
		 * @param $hooks
		 * @param $hook
		 * @param $component
		 * @param $callback
		 * @param $priority
		 * @param $accepted_args
		 * @return array
		 */
		private function add($hooks, $hook, $component, $callback, $priority, $accepted_args)
		{

			$hooks[] = array(
				'hook' => $hook,
				'component' => $component,
				'callback' => $callback,
				'priority' => $priority,
				'accepted_args' => $accepted_args
			);

			return $hooks;

		}

		/**
		 * Register the filters and actions with WordPress.
		 */
		public function run()
		{
			foreach ($this->filters as $hook) {
				add_filter($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
			}
			foreach ($this->actions as $hook) {
				add_action($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
			}
		}
	}
}