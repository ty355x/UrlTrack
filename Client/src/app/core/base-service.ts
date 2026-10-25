import { environment } from 'src/environments/environment';

export class BaseService {
    protected apiPath = environment.apiPath;

    constructor() {
    }
}
