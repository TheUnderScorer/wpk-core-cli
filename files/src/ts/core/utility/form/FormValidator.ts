import { checkRequired } from './form';

export default class FormValidator
{

    protected $form: JQuery;
    protected isValid: boolean;

    constructor( $form: JQuery )
    {
        this.$form = $form;
    }

    public checkRequired(): boolean
    {
        this.isValid = checkRequired( this.$form );

        return this.isValid;
    }

    // TODO Implement
    public validateFields(): boolean
    {
        return true;
    }

}
