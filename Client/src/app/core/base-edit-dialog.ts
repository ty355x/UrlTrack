export class BaseEditDialog {
    errors: any = [];

    getError(key: string) {
        if (this.errors.hasOwnProperty(key) && this.errors[key].length > 0) {
            return this.errors[key][0];
        }

        return null;
    }
}
