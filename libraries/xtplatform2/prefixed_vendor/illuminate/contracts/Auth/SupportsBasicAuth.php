<?php /* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:20:24 */

namespace XTP_BUILD\Illuminate\Contracts\Auth;

interface SupportsBasicAuth
{
    /**
     * Attempt to authenticate using HTTP Basic Auth.
     *
     * @param  string  $field
     * @param  array  $extraConditions
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function basic($field = 'email', $extraConditions = []);

    /**
     * Perform a stateless HTTP Basic login attempt.
     *
     * @param  string  $field
     * @param  array  $extraConditions
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function onceBasic($field = 'email', $extraConditions = []);
}