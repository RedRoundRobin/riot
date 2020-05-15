package com.redroundrobin.thirema.apirest.utils;

import com.redroundrobin.thirema.apirest.service.postgres.UserService;
import com.redroundrobin.thirema.apirest.utils.exception.TelegramChatNotFoundException;
import com.redroundrobin.thirema.apirest.utils.exception.UserDisabledException;
import io.jsonwebtoken.ExpiredJwtException;
import java.io.IOException;
import java.util.Set;
import javax.servlet.FilterChain;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.security.web.authentication.WebAuthenticationDetailsSource;
import org.springframework.web.filter.OncePerRequestFilter;

public class JwtRequestFilter extends OncePerRequestFilter {

  private final JwtUtil jwtUtil;

  private final UserService userService;

  private final Set<String> publicRequests;

  private UserDetails getUserDetailsByType(String type, String username) {
    try {
      switch (type) {
        case "webapp":
        case "tfa":
          return this.userService.loadUserByEmail(username);
        case "telegram":
          return this.userService.loadUserByTelegramName(username);
        default:
          return null;
      }
    } catch (UsernameNotFoundException | UserDisabledException
        | TelegramChatNotFoundException ue) {
      return null;
    }
  }

  private int authorizedRequest(HttpServletRequest request) {
    final String authorizationHeader = request.getHeader("Authorization");

    if (authorizationHeader != null && authorizationHeader.startsWith("Bearer ")) {

      String jwt;
      String type;
      String username;

      try {
        jwt = authorizationHeader.substring(7);
        type = jwtUtil.extractType(jwt);
        username = jwtUtil.extractUsername(jwt);

      } catch (ExpiredJwtException eje) {
        return 419;
      }

      // check if request with normal token or request to "/auth/tfa" with tfa token
      // block all calls to api if no token provided and permit only "/auth/tfa" with tfa token
      if (username != null
          && SecurityContextHolder.getContext().getAuthentication() == null
          && (!jwtUtil.isTfa(jwt) || request.getRequestURI().equals("/auth/tfa"))) {

        UserDetails userDetails = getUserDetailsByType(type, username);

        if (userDetails != null && jwtUtil.validateToken(jwt, userDetails)) {

          UsernamePasswordAuthenticationToken usernamePasswordAuthenticationToken =
              new UsernamePasswordAuthenticationToken(userDetails, null,
                  userDetails.getAuthorities());
          usernamePasswordAuthenticationToken
              .setDetails(new WebAuthenticationDetailsSource().buildDetails(request));
          SecurityContextHolder.getContext().setAuthentication(usernamePasswordAuthenticationToken);

          return 200;
        }
      }
    }
    return 401;
  }

  public JwtRequestFilter(JwtUtil jwtUtil, UserService userService, Set<String> publicRequests) {
    this.jwtUtil = jwtUtil;
    this.userService = userService;
    this.publicRequests = publicRequests;
  }

  @Override
  protected void doFilterInternal(HttpServletRequest request, HttpServletResponse response,
                                  FilterChain chain)
      throws ServletException, IOException {
    // if it is a request that needs authentication then check jwt, else jump jwt check
    if (publicRequests.stream().anyMatch(request.getRequestURI()::equals)) {
      chain.doFilter(request, response);
    } else {
      int status = authorizedRequest(request);
      if (status == 200) {
        chain.doFilter(request, response);
      } else {
        response.setStatus(status);
      }
    }
  }
}
