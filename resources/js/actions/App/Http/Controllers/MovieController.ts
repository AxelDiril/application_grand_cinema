import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\MovieController::getMovieList
 * @see app/Http/Controllers/MovieController.php:19
 * @route '/movies_list'
 */
export const getMovieList = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getMovieList.url(options),
    method: 'get',
})

getMovieList.definition = {
    methods: ["get","head"],
    url: '/movies_list',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MovieController::getMovieList
 * @see app/Http/Controllers/MovieController.php:19
 * @route '/movies_list'
 */
getMovieList.url = (options?: RouteQueryOptions) => {
    return getMovieList.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\MovieController::getMovieList
 * @see app/Http/Controllers/MovieController.php:19
 * @route '/movies_list'
 */
getMovieList.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getMovieList.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\MovieController::getMovieList
 * @see app/Http/Controllers/MovieController.php:19
 * @route '/movies_list'
 */
getMovieList.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getMovieList.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\MovieController::getMovieList
 * @see app/Http/Controllers/MovieController.php:19
 * @route '/movies_list'
 */
    const getMovieListForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getMovieList.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\MovieController::getMovieList
 * @see app/Http/Controllers/MovieController.php:19
 * @route '/movies_list'
 */
        getMovieListForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getMovieList.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\MovieController::getMovieList
 * @see app/Http/Controllers/MovieController.php:19
 * @route '/movies_list'
 */
        getMovieListForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getMovieList.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getMovieList.form = getMovieListForm
/**
* @see \App\Http\Controllers\MovieController::getMovie
 * @see app/Http/Controllers/MovieController.php:52
 * @route '/movie/{movie_id}'
 */
export const getMovie = (args: { movie_id: string | number } | [movie_id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getMovie.url(args, options),
    method: 'get',
})

getMovie.definition = {
    methods: ["get","head"],
    url: '/movie/{movie_id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MovieController::getMovie
 * @see app/Http/Controllers/MovieController.php:52
 * @route '/movie/{movie_id}'
 */
getMovie.url = (args: { movie_id: string | number } | [movie_id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { movie_id: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    movie_id: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        movie_id: args.movie_id,
                }

    return getMovie.definition.url
            .replace('{movie_id}', parsedArgs.movie_id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\MovieController::getMovie
 * @see app/Http/Controllers/MovieController.php:52
 * @route '/movie/{movie_id}'
 */
getMovie.get = (args: { movie_id: string | number } | [movie_id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getMovie.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\MovieController::getMovie
 * @see app/Http/Controllers/MovieController.php:52
 * @route '/movie/{movie_id}'
 */
getMovie.head = (args: { movie_id: string | number } | [movie_id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getMovie.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\MovieController::getMovie
 * @see app/Http/Controllers/MovieController.php:52
 * @route '/movie/{movie_id}'
 */
    const getMovieForm = (args: { movie_id: string | number } | [movie_id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getMovie.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\MovieController::getMovie
 * @see app/Http/Controllers/MovieController.php:52
 * @route '/movie/{movie_id}'
 */
        getMovieForm.get = (args: { movie_id: string | number } | [movie_id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getMovie.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\MovieController::getMovie
 * @see app/Http/Controllers/MovieController.php:52
 * @route '/movie/{movie_id}'
 */
        getMovieForm.head = (args: { movie_id: string | number } | [movie_id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getMovie.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getMovie.form = getMovieForm
const MovieController = { getMovieList, getMovie }

export default MovieController