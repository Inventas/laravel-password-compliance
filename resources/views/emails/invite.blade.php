<x-mail::message>

# Hello {{ $name ? e($name) : 'there' }},

You have been invited to access the application. Below are the details you'll need to sign in for the first time.

**Email address:** {{ e($email) }}

**Initial password:** `{{ e($initialPassword) }}`

To sign in, use the email address above and the initial password. After your first login you'll have to change your password.

Thanks!

</x-mail::message>
