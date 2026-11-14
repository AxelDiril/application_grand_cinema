import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ScreeningController::getScreeningsList
 * @see app/Http/Controllers/ScreeningController.php:20
 * @route '/screenings_list'
 */
export const getScreeningsList = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getScreeningsList.url(options),
    method: 'get',
})

getScreeningsList.definition = {
    methods: ["get","head"],
    url: '/screenings_list',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ScreeningController::getScreeningsList
 * @see app/Http/Controllers/ScreeningController.php:20
 * @route '/screenings_list'
 */
getScreeningsList.url = (options?: RouteQueryOptions) => {
    return getScreeningsList.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ScreeningController::getScreeningsList
 * @see app/Http/Controllers/ScreeningController.php:20
 * @route '/screenings_list'
 */
getScreeningsList.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getScreeningsList.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ScreeningController::getScreeningsList
 * @see app/Http/Controllers/ScreeningController.php:20
 * @route '/screenings_list'
 */
getScreeningsList.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getScreeningsList.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ScreeningController::getScreeningsList
 * @see app/Http/Controllers/ScreeningController.php:20
 * @route '/screenings_list'
 */
    const getScreeningsListForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getScreeningsList.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ScreeningController::getScreeningsList
 * @see app/Http/Controllers/ScreeningController.php:20
 * @route '/screenings_list'
 */
        getScreeningsListForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getScreeningsList.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ScreeningController::getScreeningsList
 * @see app/Http/Controllers/ScreeningController.php:20
 * @route '/screenings_list'
 */
        getScreeningsListForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getScreeningsList.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getScreeningsList.form = getScreeningsListForm
const ScreeningController = { getScreeningsList }

export default ScreeningController