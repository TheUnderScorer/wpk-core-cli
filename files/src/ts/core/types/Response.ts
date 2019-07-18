import Message from './Message';

export default interface Response
{
    result: any;
    messages: Message[];
    error: boolean;
    additional: any;
    redirectUrl: string;
    statusCode: number;
}
