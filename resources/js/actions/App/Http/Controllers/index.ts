import MovieController from './MovieController'
import ScreeningController from './ScreeningController'
import Settings from './Settings'
const Controllers = {
    MovieController: Object.assign(MovieController, MovieController),
ScreeningController: Object.assign(ScreeningController, ScreeningController),
Settings: Object.assign(Settings, Settings),
}

export default Controllers