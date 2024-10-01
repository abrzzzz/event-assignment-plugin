# wp-event-assignment
This project is an assignment, 

- For quick setup I prefer to use [WP LOCAL](https://localwp.com/)

- In this project I used my own open-source framework [WPINT](https://github.com/wpint/wpint-plugin), It's on top of laravel's application contract and gives us many powers such as (DI, Eloquent, Dependency Resolver, Routing, MVC, ... )

- Also the plugin has been integrated with [React.js](https://react.dev/) and [Inertia.js](https://inertiajs.com/)

### Install  
- Run `git clone https://github.com/abrzzzz/wp-event-assignment`
- go to the plugin directory (`cd your_dirs/wp-content/plugins/wp-event-assignment`)
- Run `composer install`
- Run `npm install`
- Run `npm run dev`

### Caveats

- As mentioned, I used React in this project, please consider that the `/events/*` pages will not be rendered until you run dev script of package.json 

- You can visit `site_url/events` in order to see events listing page

- Also use `[event_list]` shortcode to print simple event listing 

- For more information you can checkout the providers directory

- For simplicity and time efficiency, I just cut off a lot and coded everything light as much as I could


### Thanks
